<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Material;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Material::query();

        // Handle search
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhere('supplier_name', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%");
            });
        }

        // Handle category filter
        if ($request->filled('category')) {
            $query->where('category', $request->get('category'));
        }

        // Handle status filter
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        // Handle stock status filter
        if ($request->filled('stock_status')) {
            $stockStatus = $request->get('stock_status');
            switch ($stockStatus) {
                case 'out_of_stock':
                    $query->where('current_stock', 0);
                    break;
                case 'low_stock':
                    $query->whereColumn('current_stock', '<=', 'minimum_stock')
                          ->where('current_stock', '>', 0);
                    break;
                case 'available':
                    $query->whereColumn('current_stock', '>', 'minimum_stock');
                    break;
            }
        }

        $materials = $query->latest()->paginate(15);

        // Get unique categories for filter
        $categories = Material::distinct('category')->pluck('category')->filter();

        // Get all materials for stats
        $allMaterials = Material::all();

        // Preserve filters in pagination links
        $materials->appends($request->only(['search', 'category', 'status', 'stock_status']));

        return view('settings.materials', compact('materials', 'categories', 'allMaterials'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('settings.materials-create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'unit' => 'required|string|max:255',
            ]);

            // Create material unit first if it doesn't exist
            $materialUnit = \App\Models\MaterialUnit::firstOrCreate(
                ['name' => $validated['unit']],
                [
                    'name' => $validated['unit'],
                    'symbol' => $validated['unit'],
                    'type' => 'weight',
                    'is_active' => true
                ]
            );

            // Set default values for required fields
            $materialData = [
                'name' => $validated['name'],
                'material_unit_id' => $materialUnit->id,
                'category' => 'عام', // Default category
                'minimum_stock' => 0,
                'current_stock' => 0,
                'status' => 'active'
            ];

            Material::create($materialData);

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'تم إضافة المادة بنجاح']);
            }

            return redirect()->route('settings.materials')
                ->with('success', 'تم إضافة المادة بنجاح');
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['error' => true, 'message' => 'حدث خطأ: ' . $e->getMessage()], 500);
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'حدث خطأ: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Material $material)
    {
        return view('settings.materials-show', compact('material'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Material $material)
    {
        return view('settings.materials-edit', compact('material'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Material $material)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'unit' => 'required|string|max:255',
            ]);

            // Create material unit first if it doesn't exist
            $materialUnit = \App\Models\MaterialUnit::firstOrCreate(
                ['name' => $validated['unit']],
                [
                    'name' => $validated['unit'],
                    'symbol' => $validated['unit'],
                    'type' => 'weight',
                    'is_active' => true
                ]
            );

            $material->update([
                'name' => $validated['name'],
                'material_unit_id' => $materialUnit->id
            ]);

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'تم تحديث المادة بنجاح']);
            }

            return redirect()->route('settings.materials')
                ->with('success', 'تم تحديث المادة بنجاح');
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['error' => true, 'message' => 'حدث خطأ: ' . $e->getMessage()], 500);
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'حدث خطأ: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Material $material)
    {
        $material->delete();

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'تم حذف المادة بنجاح']);
        }

        return redirect()->route('settings.materials')
            ->with('success', 'تم حذف المادة بنجاح');
    }

    /**
     * Get content for AJAX tab loading
     */
    public function content(Request $request)
    {
        $query = Material::query();

        // Handle search
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhere('supplier_name', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%");
            });
        }

        // Handle category filter
        if ($request->filled('category')) {
            $query->where('category', $request->get('category'));
        }

        // Handle status filter
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        // Handle stock status filter
        if ($request->filled('stock_status')) {
            $stockStatus = $request->get('stock_status');
            switch ($stockStatus) {
                case 'out_of_stock':
                    $query->where('current_stock', 0);
                    break;
                case 'low_stock':
                    $query->whereColumn('current_stock', '<=', 'minimum_stock')
                          ->where('current_stock', '>', 0);
                    break;
                case 'available':
                    $query->whereColumn('current_stock', '>', 'minimum_stock');
                    break;
            }
        }

        $materials = $query->orderBy('name')->paginate(10);

        // Calculate statistics
        $totalMaterials = Material::count();
        $lowStockMaterials = Material::whereColumn('current_stock', '<=', 'minimum_stock')
                                   ->where('current_stock', '>', 0)
                                   ->count();
        $outOfStockMaterials = Material::where('current_stock', 0)->count();
        $activeMaterials = Material::where('status', 'active')->count();

        return view('settings.partials.materials-content', compact(
            'materials',
            'totalMaterials',
            'lowStockMaterials',
            'outOfStockMaterials',
            'activeMaterials'
        ));
    }
}
