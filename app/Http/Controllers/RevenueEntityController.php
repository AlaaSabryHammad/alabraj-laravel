<?php

namespace App\Http\Controllers;

use App\Models\RevenueEntity;
use Illuminate\Http\Request;

class RevenueEntityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $entities = RevenueEntity::orderBy('name')->paginate(20);
        return view('settings.revenue-entities-index', compact('entities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('revenue-entities.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:client,government,company,individual',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'tax_number' => 'nullable|string|max:20',
            'commercial_record' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive'
        ]);

        RevenueEntity::create($validated);

        return redirect()->route('revenue-entities.index')
            ->with('success', 'تم إنشاء جهة الإيراد بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(RevenueEntity $revenueEntity)
    {
        return view('revenue-entities.show', compact('revenueEntity'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RevenueEntity $revenueEntity)
    {
        $entity = $revenueEntity;
        return view('settings.revenue-entities-edit', compact('entity'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RevenueEntity $revenueEntity)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:client,government,company,individual',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'tax_number' => 'nullable|string|max:20',
            'commercial_record' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive'
        ]);

        $revenueEntity->update($validated);

        return redirect()->route('revenue-entities.index')
            ->with('success', 'تم تحديث جهة الإيراد بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RevenueEntity $revenueEntity)
    {
        // Check if entity is used in any revenue vouchers
        if ($revenueEntity->revenueVouchers()->exists()) {
            return redirect()->route('revenue-entities.index')
                ->with('error', 'لا يمكن حذف هذه الجهة لأنها مرتبطة بسندات قبض');
        }

        $revenueEntity->delete();

        return redirect()->route('revenue-entities.index')
            ->with('success', 'تم حذف جهة الإيراد بنجاح');
    }

    /**
     * Get content for settings page.
     */
    public function getContent()
    {
        $revenueEntities = RevenueEntity::orderBy('name')->get();
        $entitiesCount = RevenueEntity::count();
        $activeEntitiesCount = RevenueEntity::where('status', 'active')->count();

        return view('revenue-entities.content', compact(
            'revenueEntities',
            'entitiesCount',
            'activeEntitiesCount'
        ));
    }
}
