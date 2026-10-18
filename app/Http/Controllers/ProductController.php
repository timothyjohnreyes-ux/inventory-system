<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $categoryId = $request->input('category_id');

        $products = Product::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%");
                });
            })
            ->when($categoryId, function ($query, $categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->paginate(10)
            ->withQueryString();

        $categories = Category::all();

        return view('products.index', compact(
            'products',
            'categories',
            'search',
            'categoryId'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();

        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'category_id' => 'required|exists:categories,id',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0|max:99999999.99|decimal:0,2',
            'description' => 'nullable|string',
        ]);

        do {
            $sku = 'SKU-'.random_int(100000, 999999);
        } while (Product::where('sku', $sku)->exists());

        // Add the generated SKU to the validated data
        $validated['sku'] = $sku;

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Product added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::with('category')->findOrFail($id);
        $categories = Category::all();

        return view('products.show', compact('product', 'categories'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();

        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);
        $returnToDetails = $request->input('form_type') === 'edit_product_detail';

        $validated = $request->validate([
            'name' => 'required',
            'category_id' => 'required|exists:categories,id',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0|max:99999999.99|decimal:0,2',
            'description' => 'nullable|string',
        ]);

        $product->fill($validated);

        if (! $product->isDirty()) {
            return redirect()
                ->route($returnToDetails ? 'products.show' : 'products.index', $returnToDetails ? $product->id : [])
                ->with('info', 'No changes were made to the product.');
        }

        $product->save();

        return redirect()
            ->route($returnToDetails ? 'products.show' : 'products.index', $returnToDetails ? $product->id : [])
            ->with('success', 'Product updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);

        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Product deleted successfully!');
    }
}
