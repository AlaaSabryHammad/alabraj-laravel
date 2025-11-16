<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Equipment;
use App\Models\EquipmentMaintenance;
use App\Models\Project;
use App\Models\Transport;
use App\Models\Document;
use App\Models\Finance;
use App\Models\RevenueVoucher;
use App\Models\Material;
use App\Models\User;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        // Get dashboard statistics
        $stats = [
            'total_employees' => Employee::count(),
            'available_equipment' => Equipment::where('status', 'available')->count(),
            'active_projects' => Project::where('status', 'active')->count(),
            'monthly_revenue' => $this->getMonthlyRevenue(),
            'daily_trips' => Transport::whereDate('created_at', today())->count(),
            'new_documents' => Document::whereMonth('created_at', now()->month)->count(),
        ];

        // Get chart data (last 6 months)
        $chartData = $this->getChartData();

        // Get recent activities
        $recentActivities = $this->getRecentActivities();

        return view('dashboard.index', compact('stats', 'chartData', 'recentActivities'));
    }

    private function getMonthlyRevenue()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // حساب الإيرادات من جدول Finance
        $financeIncome = Finance::where('type', Finance::TYPE_INCOME)
            ->where('status', Finance::STATUS_COMPLETED)
            ->whereMonth('transaction_date', $currentMonth)
            ->whereYear('transaction_date', $currentYear)
            ->sum('amount');

        // حساب الإيرادات من جدول RevenueVoucher (مع التحقق من وجود الجدول)
        $revenueVoucherIncome = 0;
        try {
            if (Schema::hasTable('revenue_vouchers')) {
                $revenueVoucherIncome = RevenueVoucher::where('status', 'approved')
                    ->whereMonth('voucher_date', $currentMonth)
                    ->whereYear('voucher_date', $currentYear)
                    ->sum('amount');
            }
        } catch (\Exception $e) {
            // إذا كان هناك خطأ، نتجاهل RevenueVoucher
            $revenueVoucherIncome = 0;
        }

        // إجمالي الإيرادات
        $totalRevenue = $financeIncome + $revenueVoucherIncome;

        return $totalRevenue ?: 0;
    }

    private function getChartData()
    {
        // Get last 6 months financial data
        $chartData = [];
        $currentYear = Carbon::now()->year;

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthNumber = $month->month;
            $monthName = $month->locale('ar')->format('F');

            // حساب الإيرادات الفعلية للشهر
            $financeIncome = Finance::where('type', Finance::TYPE_INCOME)
                ->where('status', Finance::STATUS_COMPLETED)
                ->whereMonth('transaction_date', $monthNumber)
                ->whereYear('transaction_date', $currentYear)
                ->sum('amount');

            // حساب إيرادات RevenueVoucher (مع التحقق من وجود الجدول)
            $revenueVoucherIncome = 0;
            try {
                if (Schema::hasTable('revenue_vouchers')) {
                    $revenueVoucherIncome = RevenueVoucher::where('status', 'approved')
                        ->whereMonth('voucher_date', $monthNumber)
                        ->whereYear('voucher_date', $currentYear)
                        ->sum('amount');
                }
            } catch (\Exception $e) {
                $revenueVoucherIncome = 0;
            }

            $totalRevenue = $financeIncome + $revenueVoucherIncome;

            // حساب المصروفات الفعلية للشهر
            $expenses = Finance::where('type', Finance::TYPE_EXPENSE)
                ->where('status', Finance::STATUS_COMPLETED)
                ->whereMonth('transaction_date', $monthNumber)
                ->whereYear('transaction_date', $currentYear)
                ->sum('amount');

            $chartData[] = [
                'name' => $monthName,
                'revenue' => $totalRevenue ?: 0,
                'expenses' => $expenses ?: 0
            ];
        }

        return $chartData;
    }

    private function getRecentActivities()
    {
        $activities = collect();

        // Add timestamps for proper sorting
        $activitiesWithTimestamp = collect();

        // Recent employee activities
        $recentEmployees = Employee::latest()->take(2)->get();
        foreach ($recentEmployees as $employee) {
            $activitiesWithTimestamp->push([
                'type' => 'employee',
                'message' => 'تم إضافة موظف جديد: ' . $employee->name,
                'time' => $employee->created_at->diffForHumans(),
                'timestamp' => $employee->created_at,
                'icon' => 'ri-user-add-fill',
                'color' => 'green'
            ]);
        }

        // Recent equipment activities
        $equipment = Equipment::latest()->take(3)->get();
        foreach ($equipment as $item) {
            $activitiesWithTimestamp->push([
                'type' => 'equipment',
                'message' => 'تم تسجيل معدة جديدة: ' . $item->name,
                'time' => $item->created_at->diffForHumans(),
                'timestamp' => $item->created_at,
                'icon' => 'ri-tools-fill',
                'color' => 'blue'
            ]);
        }

        // Recent projects
        $projects = Project::latest()->take(2)->get();
        foreach ($projects as $project) {
            $activitiesWithTimestamp->push([
                'type' => 'project',
                'message' => 'بدأ مشروع جديد: ' . $project->name,
                'time' => $project->created_at->diffForHumans(),
                'timestamp' => $project->created_at,
                'icon' => 'ri-building-fill',
                'color' => 'green'
            ]);
        }

        // Recent materials activity
        try {
            $materials = Material::latest()->take(2)->get();
            foreach ($materials as $material) {
                $activitiesWithTimestamp->push([
                    'type' => 'material',
                    'message' => 'تم إضافة مادة جديدة: ' . $material->name,
                    'time' => $material->created_at->diffForHumans(),
                    'timestamp' => $material->created_at,
                    'icon' => 'ri-archive-fill',
                    'color' => 'orange'
                ]);
            }
        } catch (\Exception $e) {
            // Handle if Material model doesn't exist
        }

        // Recent maintenance activities
        try {
            $maintenances = EquipmentMaintenance::with('equipment')->latest()->take(2)->get();
            foreach ($maintenances as $maintenance) {
                $activitiesWithTimestamp->push([
                    'type' => 'maintenance',
                    'message' => 'صيانة للمعدة: ' . ($maintenance->equipment->name ?? 'غير محدد'),
                    'time' => $maintenance->created_at->diffForHumans(),
                    'timestamp' => $maintenance->created_at,
                    'icon' => 'ri-settings-2-fill',
                    'color' => 'yellow'
                ]);
            }
        } catch (\Exception $e) {
            // Handle if EquipmentMaintenance model doesn't exist
        }

        // Recent documents
        $documents = Document::latest()->take(2)->get();
        foreach ($documents as $document) {
            $activitiesWithTimestamp->push([
                'type' => 'document',
                'message' => 'تم رفع مستند: ' . $document->name,
                'time' => $document->created_at->diffForHumans(),
                'timestamp' => $document->created_at,
                'icon' => 'ri-file-text-fill',
                'color' => 'purple'
            ]);
        }

        // Recent warehouse activities (if exists)
        try {
            $warehouses = Warehouse::latest()->take(1)->get();
            foreach ($warehouses as $warehouse) {
                $activitiesWithTimestamp->push([
                    'type' => 'warehouse',
                    'message' => 'تم إنشاء مخزن جديد: ' . $warehouse->name,
                    'time' => $warehouse->created_at->diffForHumans(),
                    'timestamp' => $warehouse->created_at,
                    'icon' => 'ri-building-2-fill',
                    'color' => 'indigo'
                ]);
            }
        } catch (\Exception $e) {
            // Handle if Warehouse model doesn't exist
        }

        // Recent user activities
        $recentUsers = User::where('created_at', '>', now()->subDays(7))->latest()->take(2)->get();
        foreach ($recentUsers as $user) {
            $activitiesWithTimestamp->push([
                'type' => 'user',
                'message' => 'انضم مستخدم جديد: ' . $user->name,
                'time' => $user->created_at->diffForHumans(),
                'timestamp' => $user->created_at,
                'icon' => 'ri-account-circle-fill',
                'color' => 'cyan'
            ]);
        }

        // Sort by timestamp and take latest 10
        return $activitiesWithTimestamp->sortByDesc('timestamp')->take(10)->values();
    }
}
