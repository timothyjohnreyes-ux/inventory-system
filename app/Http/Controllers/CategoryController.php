<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::paginate(10);

        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'unique:categories,name',
                'regex:/^[A-Za-z0-9 ]+$/',
            ],
            'description' => ['nullable', 'string'],
        ]);

        Category::create($validated);

        return redirect()->route('categories.index')->with('success', 'Category added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::findOrFail($id);
        $products = $category->products()
            ->latest('id')
            ->paginate(8);

        return view('categories.show', compact('category', 'products'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = Category::findOrFail($id);

        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = Category::findOrFail($id);
        $returnToDetails = $request->input('form_type') === 'edit_category_detail';

        $validated = $request->validate([
            'name' => [
                'required',
                'unique:categories,name,'.$category->id,
                'regex:/^[A-Za-z0-9 ]+$/',
            ],
            'description' => ['nullable', 'string'],

        ]);

        $category->fill($validated);

        if (! $category->isDirty()) {
            return redirect()
                ->route($returnToDetails ? 'categories.show' : 'categories.index', $returnToDetails ? $category->id : [])
                ->with('info', 'No changes were made to the category.');
        }

        $category->save();

        return redirect()
            ->route($returnToDetails ? 'categories.show' : 'categories.index', $returnToDetails ? $category->id : [])
            ->with('success', 'Category updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);

        if ($category->products()->exists()) {
            return redirect()
                ->route('categories.index')
                ->with('error', 'Cannot delete a category that still has products.');
        }

        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Category deleted successfully!');
    }
}
