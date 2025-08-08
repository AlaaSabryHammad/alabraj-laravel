<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SparePartTransaction;
use App\Models\WarehouseInventory;
use App\Models\Location;
use App\Models\SparePart;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SparePartReportController extends Controller
{
    /**
     * عرض صفحة التقارير الرئيسية
     */
    public function index()
    {
        $warehouses = Location::whereHas('locationType', function($query) {
            $query->where('name', 'مستودع');
        })->get();

        return view('reports.spare-parts.index', compact('warehouses'));
    }

    /**
     * التقرير اليومي لقطع الغيار
     */
    public function daily(Request $request)
    {
        $date = $request->get('date', Carbon::today()->format('Y-m-d'));
        $warehouseId = $request->get('warehouse_id');
        
        $query = SparePartTransaction::with(['sparePart', 'location', 'user'])
            ->whereDate('transaction_date', $date);
            
        if ($warehouseId) {
            $query->where('location_id', $warehouseId);
        }
        
        $transactions = $query->orderBy('transaction_date', 'desc')->get();
        
        // إحصائيات اليوم
        $stats = [
            'total_received' => $transactions->where('transaction_type', 'استلام')->sum('quantity'),
            'total_exported' => $transactions->where('transaction_type', 'تصدير')->sum('quantity'),
            'total_received_value' => $transactions->where('transaction_type', 'استلام')->sum('total_amount'),
            'total_exported_value' => $transactions->where('transaction_type', 'تصدير')->sum('total_amount'),
            'unique_parts' => $transactions->pluck('spare_part_id')->unique()->count(),
        ];

        $warehouses = Location::whereHas('locationType', function($query) {
            $query->where('name', 'مستودع');
        })->get();

        return view('reports.spare-parts.daily', compact('transactions', 'stats', 'date', 'warehouses', 'warehouseId'));
    }

    /**
     * التقرير الشهري لقطع الغيار
     */
    public function monthly(Request $request)
    {
        $month = $request->get('month', Carbon::now()->format('Y-m'));
        $warehouseId = $request->get('warehouse_id');
        
        $startDate = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $endDate = Carbon::createFromFormat('Y-m', $month)->endOfMonth();
        
        $query = SparePartTransaction::with(['sparePart', 'location', 'user'])
            ->whereBetween('transaction_date', [$startDate, $endDate]);
            
        if ($warehouseId) {
            $query->where('location_id', $warehouseId);
        }
        
        $transactions = $query->orderBy('transaction_date', 'desc')->get();
        
        // إحصائيات الشهر
        $stats = [
            'total_received' => $transactions->where('transaction_type', 'استلام')->sum('quantity'),
            'total_exported' => $transactions->where('transaction_type', 'تصدير')->sum('quantity'),
            'total_received_value' => $transactions->where('transaction_type', 'استلام')->sum('total_amount'),
            'total_exported_value' => $transactions->where('transaction_type', 'تصدير')->sum('total_amount'),
            'unique_parts' => $transactions->pluck('spare_part_id')->unique()->count(),
            'days_with_activity' => $transactions->pluck('transaction_date')->map(function($date) {
                return Carbon::parse($date)->format('Y-m-d');
            })->unique()->count(),
        ];

        // إحصائيات يومية للشهر
        $dailyStats = $transactions->groupBy(function($transaction) {
            return Carbon::parse($transaction->transaction_date)->format('Y-m-d');
        })->map(function($dayTransactions, $date) {
            return [
                'date' => $date,
                'received' => $dayTransactions->where('transaction_type', 'استلام')->sum('quantity'),
                'exported' => $dayTransactions->where('transaction_type', 'تصدير')->sum('quantity'),
                'received_value' => $dayTransactions->where('transaction_type', 'استلام')->sum('total_amount'),
                'exported_value' => $dayTransactions->where('transaction_type', 'تصدير')->sum('total_amount'),
            ];
        })->sortKeys();

        // أفضل 10 قطع غيار استخداماً
        $topParts = $transactions->where('transaction_type', 'تصدير')
            ->groupBy('spare_part_id')
            ->map(function($partTransactions) {
                $sparePart = $partTransactions->first()->sparePart;
                return [
                    'spare_part' => $sparePart,
                    'total_exported' => $partTransactions->sum('quantity'),
                    'total_value' => $partTransactions->sum('total_amount'),
                    'transaction_count' => $partTransactions->count(),
                ];
            })
            ->sortByDesc('total_exported')
            ->take(10);

        $warehouses = Location::whereHas('locationType', function($query) {
            $query->where('name', 'مستودع');
        })->get();

        return view('reports.spare-parts.monthly', compact(
            'transactions', 'stats', 'dailyStats', 'topParts', 'month', 'warehouses', 'warehouseId'
        ));
    }

    /**
     * تقرير حالة المخزون
     */
    public function inventory(Request $request)
    {
        $warehouseId = $request->get('warehouse_id');
        $lowStockOnly = $request->get('low_stock_only', false);
        
        $query = WarehouseInventory::with(['sparePart', 'location']);
        
        if ($warehouseId) {
            $query->where('location_id', $warehouseId);
        }
        
        if ($lowStockOnly) {
            $query->whereHas('sparePart', function($q) use ($query) {
                $q->whereRaw('warehouse_inventories.current_stock <= spare_parts.minimum_stock');
            });
        }
        
        $inventory = $query->orderBy('current_stock', 'asc')->get();
        
        // إحصائيات المخزون
        $stats = [
            'total_parts' => $inventory->count(),
            'total_stock' => $inventory->sum('current_stock'),
            'total_value' => $inventory->sum('total_value'),
            'low_stock_items' => $inventory->filter(function($item) {
                return $item->current_stock <= $item->sparePart->minimum_stock;
            })->count(),
            'out_of_stock_items' => $inventory->where('current_stock', 0)->count(),
        ];

        $warehouses = Location::whereHas('locationType', function($query) {
            $query->where('name', 'مستودع');
        })->get();

        return view('reports.spare-parts.inventory', compact('inventory', 'stats', 'warehouses', 'warehouseId', 'lowStockOnly'));
    }

    /**
     * تقرير الموظفين والتصدير
     */
    public function employees(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $warehouseId = $request->get('warehouse_id');
        
        $query = SparePartTransaction::with(['sparePart', 'location'])
            ->where('transaction_type', 'تصدير')
            ->whereBetween('transaction_date', [$startDate, $endDate]);
            
        if ($warehouseId) {
            $query->where('location_id', $warehouseId);
        }
        
        $transactions = $query->get();
        
        // تجميع حسب الموظف
        $employeeStats = $transactions->groupBy(function($transaction) {
            return $transaction->additional_data['recipient_employee_id'] ?? 'غير محدد';
        })->map(function($employeeTransactions, $employeeId) {
            $employee = null;
            if ($employeeId !== 'غير محدد' && is_numeric($employeeId)) {
                $employee = Employee::find($employeeId);
            }
            
            return [
                'employee' => $employee,
                'employee_name' => $employee ? $employee->name : 'غير محدد',
                'total_parts' => $employeeTransactions->sum('quantity'),
                'total_value' => $employeeTransactions->sum('total_amount'),
                'transaction_count' => $employeeTransactions->count(),
                'unique_parts' => $employeeTransactions->pluck('spare_part_id')->unique()->count(),
                'transactions' => $employeeTransactions,
            ];
        })->sortByDesc('total_parts');

        $warehouses = Location::whereHas('locationType', function($query) {
            $query->where('name', 'مستودع');
        })->get();

        return view('reports.spare-parts.employees', compact('employeeStats', 'startDate', 'endDate', 'warehouses', 'warehouseId'));
    }
}
