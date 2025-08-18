<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\RevenueType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RevenueTypeController extends Controller
{
    /**
     * Display the revenue types content for settings page
     */
    public function content()
    {
        $revenueTypes = RevenueType::orderBy('name')->get();

        return view('settings.partials.revenue-types-content', compact('revenueTypes'));
    }

    /**
     * Store a new revenue type
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:revenue_types,name',
            'code' => 'required|string|max:50|unique:revenue_types,code',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean'
        ], [
            'name.required' => 'اسم نوع الإيراد مطلوب',
            'name.unique' => 'اسم نوع الإيراد موجود بالفعل',
            'code.required' => 'كود نوع الإيراد مطلوب',
            'code.unique' => 'كود نوع الإيراد موجود بالفعل'
        ]);

        RevenueType::create($request->all());

        return response()->json(['success' => true, 'message' => 'تم إضافة نوع الإيراد بنجاح']);
    }

    /**
     * Update a revenue type
     */
    public function update(Request $request, RevenueType $revenueType)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('revenue_types', 'name')->ignore($revenueType->id)],
            'code' => ['required', 'string', 'max:50', Rule::unique('revenue_types', 'code')->ignore($revenueType->id)],
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean'
        ], [
            'name.required' => 'اسم نوع الإيراد مطلوب',
            'name.unique' => 'اسم نوع الإيراد موجود بالفعل',
            'code.required' => 'كود نوع الإيراد مطلوب',
            'code.unique' => 'كود نوع الإيراد موجود بالفعل'
        ]);

        $revenueType->update($request->all());

        return response()->json(['success' => true, 'message' => 'تم تحديث نوع الإيراد بنجاح']);
    }

    /**
     * Toggle revenue type status
     */
    public function toggleStatus(RevenueType $revenueType)
    {
        try {
            \Log::info('Toggle revenue type status called', [
                'id' => $revenueType->id,
                'current_status' => $revenueType->is_active,
                'new_status' => !$revenueType->is_active
            ]);

            $oldStatus = $revenueType->is_active;
            $revenueType->update(['is_active' => !$revenueType->is_active]);
            
            // Reload to get the updated status
            $revenueType->refresh();
            
            $status = $revenueType->is_active ? 'تم تفعيل' : 'تم إلغاء تفعيل';
            
            \Log::info('Toggle revenue type status completed', [
                'id' => $revenueType->id,
                'old_status' => $oldStatus,
                'new_status' => $revenueType->is_active
            ]);

            return response()->json([
                'success' => true, 
                'message' => $status . ' نوع الإيراد بنجاح',
                'new_status' => $revenueType->is_active
            ]);
        } catch (\Exception $e) {
            \Log::error('Error toggling revenue type status', [
                'id' => $revenueType->id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تغيير الحالة: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a revenue type
     */
    public function destroy(RevenueType $revenueType)
    {
        $revenueType->delete();

        return response()->json(['success' => true, 'message' => 'تم حذف نوع الإيراد بنجاح']);
    }
}
