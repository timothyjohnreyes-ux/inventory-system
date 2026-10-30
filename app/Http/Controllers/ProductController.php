<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockTransaction;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $categoryId = $request->input('category_id');

        $stockStatus = $request->input('stock_status', 'all');

        if (! in_array($stockStatus, ['all', 'low_stock', 'out_of_stock'], true)) {
            $stockStatus = 'all';
        }

        $status = $request->input('status', 'active');

        if (! in_array($status, ['active', 'archived'], true)) {
            $status = 'active';
        }

        $sortOptions = [
            'newest' => ['created_at', 'desc'],
            'oldest' => ['created_at', 'asc'],
            'name_asc' => ['name', 'asc'],
            'name_desc' => ['name', 'desc'],
            'quantity_asc' => ['quantity', 'asc'],
            'quantity_desc' => ['quantity', 'desc'],
            'price_asc' => ['price', 'asc'],
            'price_desc' => ['price', 'desc'],
        ];

        $sort = $request->input('sort', 'newest');

        if (! is_string($sort) || ! array_key_exists($sort, $sortOptions)) {
            $sort = 'newest';
        }

        $products = Product::query()->when($status === 'active', function ($query) {
            $query->where('is_active', true);
        })
            ->when($status === 'archived', function ($query) {
                $query->where('is_active', false);
            })
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%");
                });
            })
            ->when($categoryId, function ($query, $categoryId) {
                $query->where('category_id', $categoryId);
            })->when($stockStatus === 'low_stock', function ($query) {
                $query->where('quantity', '>', 0)
                    ->whereColumn('quantity', '<=', 'reorder_level');
            })->when($stockStatus === 'out_of_stock', function ($query) {
                $query->where('quantity', 0);
            })
            ->orderBy(
                $sortOptions[$sort][0],
                $sortOptions[$sort][1]
            )
            ->orderBy('id', $sortOptions[$sort][1])
            ->paginate(10)
            ->withQueryString();

        $categories = Category::all();

        return view('products.index', compact(
            'products',
            'categories',
            'search',
            'categoryId',
            'status',
            'sort',
            'stockStatus'
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
            'reorder_level' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0|max:99999999.99|decimal:0,2',
            'description' => 'nullable|string',
        ]);

        do {
            $sku = 'SKU-'.random_int(100000, 999999);
        } while (Product::where('sku', $sku)->exists());

        // Add the generated SKU to the validated data
        $validated['sku'] = $sku;

        $product = DB::transaction(function () use ($validated) {
            $product = Product::create($validated);

            if ($product->quantity > 0) {
                StockTransaction::create([
                    'product_id' => $product->id,
                    'type' => 'in',
                    'quantity' => $product->quantity,
                    'remarks' => 'Initial stock',
                ]);
            }

            return $product;
        });

        ActivityLogger::log(
            'created',
            "Product: {$product->name} (SKU: {$product->sku}) was created.",
            'Product'
        );

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
            'reorder_level' => 'required|integer|min:0',
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

        ActivityLogger::log(
            'updated',
            "Product: {$product->name} (SKU: {$product->sku}) was updated.",
            'Product'
        );

        return redirect()
            ->route($returnToDetails ? 'products.show' : 'products.index', $returnToDetails ? $product->id : [])
            ->with('success', 'Product updated successfully!');
    }

    public function toggleStatus(string $id)
    {
        $product = Product::findOrFail($id);

        $product->is_active = ! $product->is_active;
        $product->save();

        $status = $product->is_active ? 'activated' : 'deactivated';

        ActivityLogger::log(
            $status,
            "Product: {$product->name} (SKU: {$product->sku}) was {$status}.",
            'Product'
        );

        return redirect()
            ->route('products.index')
            ->with('success', "Product {$status} successfully!");
    }
}
