<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $sortOptions = [
            'newest' => ['created_at', 'desc'],
            'oldest' => ['created_at', 'asc'],
            'name_asc' => ['name', 'asc'],
            'name_desc' => ['name', 'desc'],
        ];
        $sort = $request->input('sort', 'newest');

        if (! is_string($search)) {
            $search = null;
        }

        if (! is_string($sort) || ! array_key_exists($sort, $sortOptions)) {
            $sort = 'newest';
        }

        $categories = Category::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->orderBy($sortOptions[$sort][0], $sortOptions[$sort][1])
            ->orderBy('id', $sortOptions[$sort][1])
            ->paginate(10)
            ->withQueryString();

        return view('categories.index', compact('categories', 'search', 'sort'));
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
                'string',
                'max:255',
                'unique:categories,name',
                "regex:/^[\pL\pN][\pL\pN\s&'’().-]*$/u",
            ],
            'description' => ['nullable', 'string'],
        ]);

        $category = Category::create($validated);

        ActivityLogger::log(
            'created',
            "Category: {$category->name} was created.",
            'Category'
        );

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
                'string',
                'max:255',
                'unique:categories,name,'.$category->id,
                "regex:/^[\pL\pN][\pL\pN\s&'’().-]*$/u",
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

        ActivityLogger::log(
            'updated',
            "Category: {$category->name} was updated.",
            'Category'
        );

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
