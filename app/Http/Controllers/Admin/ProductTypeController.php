<?php

namespace App\Http\Controllers\Admin;

use App\Models\ProductType;
use App\Models\ChangeoverTime;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\ProductTypeRequest;

class ProductTypeController extends Controller
{
    /**
     * Display a list of all product types.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $productTypes = ProductType::all();

        return view('admin.productType.index', compact('productTypes'));
    }

    /**
     * Show the form for creating a new product type.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $productType = new ProductType();

        $productTypes = ProductType::all();

        $hasProductTypes = ProductType::exists();

        return view('admin.productType.create', compact('productType', 'productTypes', 'hasProductTypes'));
    }

    /**
     * Store a newly created product type.
     *
     * @param ProductTypeRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ProductTypeRequest $request)
    {
        $productType = ProductType::create($request->validated());

        $existingProductTypes = ProductType::where('id', '!=', $productType->id)->get();

        if ($existingProductTypes->isNotEmpty() && $request->has('changeover_times')) {
            foreach ($existingProductTypes as $existingType) {
                $time = $request->input('changeover_times.' . $existingType->id);

                // Only insert changeover time if it's defined
                if (!is_null($time)) {
                    $this->updateChangeoverTime($productType->id, $existingType->id, $time);
                }
            }
        }
        return redirect()->route('admin.productType.index')->with('success', 'Type de produit créé avec succès et temps de changement configurés.');
    }

    /**
     * Show the form for editing an existing product type.
     *
     * @param ProductType $productType
     * @return \Illuminate\View\View
     */
    public function edit(ProductType $productType)
    {
        $productTypes = ProductType::where('id', '!=', $productType->id)->get();

        $changeoverTimes = ChangeoverTime::where('from_product_type_id', $productType->id)
            ->pluck('changeover_time', 'to_product_type_id')
            ->toArray();

        $hasProductTypes = ProductType::exists();

        return view('admin.productType.create', compact('productType', 'productTypes', 'changeoverTimes', 'hasProductTypes'));
    }

    /**
     * Update an existing product type.
     *
     * @param ProductTypeRequest $request
     * @param ProductType $productType
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProductTypeRequest $request, ProductType $productType)
    {
        $productType->update($request->validated());

        if ($request->has('changeover_times')) {
            foreach ($request->input('changeover_times') as $toTypeId => $time) {
                $this->updateChangeoverTime($productType->id, $toTypeId, $time);
            }
        }

        // Redirect back to the product types list with a success message
        return redirect()->route('admin.productType.index')->with('success', 'Type de produit mis à jour avec succès.');
    }

    /**
     * Delete a product type.
     *
     * @param ProductType $productType
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(ProductType $productType)
    {
        // Check if the product type is linked to any products before deletion
        if ($productType->products()->exists()) {
            return redirect()->route('admin.productType.index')
                ->with('error', 'Impossible de supprimer ce type de produit car des produits y sont liés.');
        }

        $productType->delete();

        return redirect()->route('admin.productType.index')
            ->with('success', 'Type de produit supprimé avec succès.');
    }

    /**
     * Utility method to create or update changeover times between product types.
     *
     * @param int $fromId
     * @param int $toId
     * @param int $time
     * @return void
     */
    private function updateChangeoverTime($fromId, $toId, $time)
    {
        // Only update if the time is not null
        if (!is_null($time)) {
            ChangeoverTime::updateOrCreate(
                [
                    'from_product_type_id' => $fromId,
                    'to_product_type_id' => $toId,
                ],
                [
                    'changeover_time' => $time,
                ]
            );
        }
    }
}
