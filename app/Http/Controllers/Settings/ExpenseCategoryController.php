<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ExpenseCategoryController extends Controller
{
    /**
     * Display the expense categories content for settings page
     */
    public function content()
    {
        $expenseCategories = ExpenseCategory::orderBy('name')->get();

        return view('settings.partials.expense-categories-content', compact('expenseCategories'));
    }

    /**
     * Store a new expense category
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:expense_categories,name',
            'code' => 'required|string|max:50|unique:expense_categories,code',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean'
        ], [
            'name.required' => 'اسم فئة المصروف مطلوب',
            'name.unique' => 'اسم فئة المصروف موجود بالفعل',
            'code.required' => 'كود فئة المصروف مطلوب',
            'code.unique' => 'كود فئة المصروف موجود بالفعل'
        ]);

        ExpenseCategory::create($request->all());

        return response()->json(['success' => true, 'message' => 'تم إضافة فئة المصروف بنجاح']);
    }

    /**
     * Update an expense category
     */
    public function update(Request $request, ExpenseCategory $expenseCategory)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('expense_categories', 'name')->ignore($expenseCategory->id)],
            'code' => ['required', 'string', 'max:50', Rule::unique('expense_categories', 'code')->ignore($expenseCategory->id)],
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean'
        ], [
            'name.required' => 'اسم فئة المصروف مطلوب',
            'name.unique' => 'اسم فئة المصروف موجود بالفعل',
            'code.required' => 'كود فئة المصروف مطلوب',
            'code.unique' => 'كود فئة المصروف موجود بالفعل'
        ]);

        $expenseCategory->update($request->all());

        return response()->json(['success' => true, 'message' => 'تم تحديث فئة المصروف بنجاح']);
    }

    /**
     * Toggle expense category status
     */
    public function toggleStatus(ExpenseCategory $expenseCategory)
    {
        try {
            \Log::info('Toggle expense category status called', [
                'id' => $expenseCategory->id,
                'current_status' => $expenseCategory->is_active,
                'new_status' => !$expenseCategory->is_active
            ]);

            $oldStatus = $expenseCategory->is_active;
            $expenseCategory->update(['is_active' => !$expenseCategory->is_active]);
            
            // Reload to get the updated status
            $expenseCategory->refresh();
            
            $status = $expenseCategory->is_active ? 'تم تفعيل' : 'تم إلغاء تفعيل';
            
            \Log::info('Toggle expense category status completed', [
                'id' => $expenseCategory->id,
                'old_status' => $oldStatus,
                'new_status' => $expenseCategory->is_active
            ]);

            return response()->json([
                'success' => true, 
                'message' => $status . ' فئة المصروف بنجاح',
                'new_status' => $expenseCategory->is_active
            ]);
        } catch (\Exception $e) {
            \Log::error('Error toggling expense category status', [
                'id' => $expenseCategory->id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تغيير الحالة: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete an expense category
     */
    public function destroy(ExpenseCategory $expenseCategory)
    {
        $expenseCategory->delete();

        return response()->json(['success' => true, 'message' => 'تم حذف فئة المصروف بنجاح']);
    }
}
