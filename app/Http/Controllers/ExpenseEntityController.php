<?php

namespace App\Http\Controllers;

use App\Models\ExpenseEntity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseEntityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $expenseEntities = ExpenseEntity::orderBy('name')->paginate(20);
        
        return view('expense-entities.index', compact('expenseEntities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('expense-entities.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:supplier,contractor,government,bank,other',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'tax_number' => 'nullable|string|max:20',
            'commercial_record' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive'
        ]);

        ExpenseEntity::create($validated);

        return redirect()->route('expense-entities.index')
                         ->with('success', 'تم إنشاء جهة الصرف بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(ExpenseEntity $expenseEntity)
    {
        return view('expense-entities.show', compact('expenseEntity'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ExpenseEntity $expenseEntity)
    {
        return view('expense-entities.edit', compact('expenseEntity'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ExpenseEntity $expenseEntity)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:supplier,contractor,government,bank,other',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'tax_number' => 'nullable|string|max:20',
            'commercial_record' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive'
        ]);

        $expenseEntity->update($validated);

        return redirect()->route('expense-entities.index')
                         ->with('success', 'تم تحديث جهة الصرف بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExpenseEntity $expenseEntity)
    {
        // Check if entity is used in any expense vouchers
        if ($expenseEntity->expenseVouchers()->exists()) {
            return redirect()->route('expense-entities.index')
                             ->with('error', 'لا يمكن حذف هذه الجهة لأنها مرتبطة بسندات صرف');
        }

        $expenseEntity->delete();

        return redirect()->route('expense-entities.index')
                         ->with('success', 'تم حذف جهة الصرف بنجاح');
    }

    /**
     * Get content for settings page.
     */
    public function getContent()
    {
        $expenseEntities = ExpenseEntity::orderBy('name')->get();
        $entitiesCount = ExpenseEntity::count();
        $activeEntitiesCount = ExpenseEntity::where('status', 'active')->count();
        
        return view('expense-entities.content', compact(
            'expenseEntities', 
            'entitiesCount', 
            'activeEntitiesCount'
        ));
    }
}
