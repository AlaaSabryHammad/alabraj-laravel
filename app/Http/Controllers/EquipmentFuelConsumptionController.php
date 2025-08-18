<?php

namespace App\Http\Controllers;

use App\Models\EquipmentFuelConsumption;
use App\Models\Equipment;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use App\Notifications\FuelConsumptionApprovalNotification;

class EquipmentFuelConsumptionController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'equipment_id' => 'required|exists:equipment,id',
            'fuel_type' => 'required|in:diesel,engine_oil,hydraulic_oil,radiator_water',
            'quantity' => 'required|numeric|min:0.01|max:99999.99',
            'consumption_date' => 'required|date|before_or_equal:today',
            'notes' => 'nullable|string|max:1000',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['approval_status'] = 'pending'; // الحالة الافتراضية

        $fuelConsumption = EquipmentFuelConsumption::create($validated);

        // إرسال إشعار لمدير الموقع إذا وجد
        $this->notifyLocationManager($fuelConsumption, $validated['equipment_id']);

        return redirect()->back()->with('success', 'تم تسجيل استهلاك المحروقات وإرسال طلب موافقة لمدير الموقع');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EquipmentFuelConsumption $equipmentFuelConsumption)
    {
        $equipmentFuelConsumption->delete();

        return redirect()->back()->with('success', 'تم حذف سجل استهلاك المحروقات بنجاح');
    }

    /**
     * Get fuel consumption data for equipment show page
     */
    public function getConsumptionsByEquipment($equipmentId)
    {
        $consumptions = EquipmentFuelConsumption::with(['user', 'approvedBy'])
            ->where('equipment_id', $equipmentId)
            ->orderBy('consumption_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($consumption) {
                $arabicMonths = [
                    1 => 'يناير',
                    2 => 'فبراير',
                    3 => 'مارس',
                    4 => 'أبريل',
                    5 => 'مايو',
                    6 => 'يونيو',
                    7 => 'يوليو',
                    8 => 'أغسطس',
                    9 => 'سبتمبر',
                    10 => 'أكتوبر',
                    11 => 'نوفمبر',
                    12 => 'ديسمبر'
                ];

                $arabicDays = [
                    'Sunday' => 'الأحد',
                    'Monday' => 'الإثنين',
                    'Tuesday' => 'الثلاثاء',
                    'Wednesday' => 'الأربعاء',
                    'Thursday' => 'الخميس',
                    'Friday' => 'الجمعة',
                    'Saturday' => 'السبت'
                ];

                $consumptionDate = $consumption->consumption_date;
                $dayName = $arabicDays[$consumptionDate->format('l')];
                $monthName = $arabicMonths[$consumptionDate->month];
                $formattedDate = $dayName . '، ' . $consumptionDate->day . ' ' . $monthName . ' ' . $consumptionDate->year;

                $createdAt = $consumption->created_at;
                $createdDayName = $arabicDays[$createdAt->format('l')];
                $createdMonthName = $arabicMonths[$createdAt->month];
                $createdFormatted = $createdDayName . '، ' . $createdAt->day . ' ' . $createdMonthName . ' ' . $createdAt->year . ' - ' . $createdAt->format('h:i A');

                return [
                    'id' => $consumption->id,
                    'fuel_type' => $consumption->fuel_type,
                    'quantity' => $consumption->quantity,
                    'consumption_date' => $consumption->consumption_date->format('Y-m-d'),
                    'consumption_date_formatted' => $formattedDate,
                    'created_at_formatted' => $createdFormatted,
                    'notes' => $consumption->notes,
                    'approval_status' => $consumption->approval_status,
                    'approval_status_text' => $consumption->approval_status_text,
                    'approval_status_color' => $consumption->approval_status_color,
                    'approved_by' => $consumption->approvedBy ? $consumption->approvedBy->name : null,
                    'approved_at' => $consumption->approved_at ? $consumption->approved_at->format('Y-m-d H:i') : null,
                    'approval_notes' => $consumption->approval_notes,
                    'user' => [
                        'name' => $consumption->user->name,
                    ]
                ];
            });

        return response()->json($consumptions);
    }

    /**
     * Get fuel consumption summary for equipment
     */
    public function getConsumptionSummary($equipmentId)
    {
        $summary = EquipmentFuelConsumption::where('equipment_id', $equipmentId)
            ->where('approval_status', 'approved') // عرض الكمية المعتمدة فقط
            ->selectRaw('fuel_type, SUM(quantity) as total_quantity, COUNT(*) as count')
            ->groupBy('fuel_type')
            ->get();

        return response()->json($summary);
    }

    /**
     * Approve fuel consumption
     */
    public function approve(Request $request, EquipmentFuelConsumption $fuelConsumption)
    {
        $validated = $request->validate([
            'approval_notes' => 'nullable|string|max:1000',
        ]);

        $fuelConsumption->update([
            'approval_status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'approval_notes' => $validated['approval_notes'] ?? null,
        ]);

        return redirect()->back()->with('success', 'تم اعتماد استهلاك المحروقات بنجاح');
    }

    /**
     * Reject fuel consumption
     */
    public function reject(Request $request, EquipmentFuelConsumption $fuelConsumption)
    {
        $validated = $request->validate([
            'approval_notes' => 'required|string|max:1000',
        ]);

        $fuelConsumption->update([
            'approval_status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'approval_notes' => $validated['approval_notes'],
        ]);

        return redirect()->back()->with('success', 'تم رفض استهلاك المحروقات');
    }

    /**
     * إرسال إشعار لمدير الموقع
     */
    private function notifyLocationManager(EquipmentFuelConsumption $fuelConsumption, int $equipmentId): void
    {
        try {
            // استخدام DB للحصول على manager_id مباشرة
            $result = DB::table('equipment')
                ->join('locations', 'equipment.location_id', '=', 'locations.id')
                ->select('locations.manager_id')
                ->where('equipment.id', $equipmentId)
                ->whereNotNull('locations.manager_id')
                ->first();

            if (!$result || !$result->manager_id) {
                return;
            }

            $manager = Employee::with('user')->find($result->manager_id);

            if ($manager && $manager->user) {
                $manager->user->notify(new FuelConsumptionApprovalNotification($fuelConsumption));
            }
        } catch (\Exception $e) {
            \Log::info('Failed to send fuel consumption notification: ' . $e->getMessage());
        }
    }
}
