<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\ProductType;
use App\Http\Requests\Admin\ProductRequest;

class ProductController extends Controller
{
    /**
     * Display a list of all products.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $products = Product::all();

        return view('admin.product.index', compact('products'));
    }

    /**
     * Show the form to create a new product.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $product = new Product();
        $productTypes = ProductType::all();

        return view('admin.product.create', compact('product', 'productTypes'));
    }

    /**
     * Store a newly created product in the database.
     *
     * @param ProductRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ProductRequest $request)
    {
        Product::create($request->validated());

        return redirect()->route('admin.product.index')->with('success', 'Produit créé avec succès.');
    }

    /**
     * Show the form to edit an existing product.
     *
     * @param Product $product
     * @return \Illuminate\View\View
     */
    public function edit(Product $product)
    {
        $productTypes = ProductType::all();

        return view('admin.product.create', compact('product', 'productTypes'));
    }

    /**
     * Update the details of an existing product.
     *
     * @param ProductRequest $request
     * @param Product $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProductRequest $request, Product $product)
    {
        $product->update($request->validated());

        return redirect()->route('admin.product.index')->with('success', 'Produit mis à jour avec succès.');
    }

    /**
     * Delete a product from the database.
     *
     * @param Product $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.product.index')->with('success', 'Produit supprimé avec succès.');
    }
}
