@extends('layouts.app')

@section('content')
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    <h1 class="text-3xl font-bold tracking-tight text-slate-900">Categories</h1>
    <button type="button" data-modal-open="addCategoryModal" class="inline-flex w-fit items-center rounded-lg bg-amber-500 px-4 py-2 text-sm font-semibold text-slate-950 hover:bg-amber-400">Add Category</button>
</div>

<div class="overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-sm">
    <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
        <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
            <tr>
                <th class="px-5 py-4 font-semibold">ID</th>
                <th class="px-5 py-4 font-semibold">Name</th>
                <th class="px-5 py-4 font-semibold">Description</th>
                <th class="px-5 py-4 font-semibold">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $category)
                <tr class="border-b border-slate-100">
                    <td class="whitespace-nowrap px-5 py-4 text-slate-500">{{ $category->id }}</td>
                    <td class="px-5 py-4 font-semibold text-slate-900">{{ $category->name }}</td>
                    <td class="max-w-md px-5 py-4 text-slate-600">{{ $category->description }}</td>
                    <td class="px-5 py-4">
                        <div class="relative" data-menu>
                            <button type="button" data-menu-toggle aria-label="Open actions for {{ $category->name }}" class="rounded-md p-2 text-lg leading-none text-slate-500 hover:bg-slate-100 hover:text-slate-900">
                                <span aria-hidden="true">&#8942;</span>
                            </button>
                            <div data-menu-content class="fixed z-50 hidden w-32 rounded-lg border border-slate-200 bg-white p-1 text-sm shadow-lg">
                                <a class="block rounded-md px-3 py-2 text-slate-700 hover:bg-slate-100" href="{{ route('categories.show', $category->id) }}">View</a>
                                <button
                                    type="button"
                                    data-modal-open="editCategoryModal"
                                    data-form-action="{{ route('categories.update', $category->id) }}"
                                    data-edit-category-id="{{ $category->id }}"
                                    data-name="{{ $category->name }}"
                                    data-description="{{ $category->description }}"
                                    class="block w-full rounded-md px-3 py-2 text-left text-sky-700 hover:bg-sky-50">
                                    Edit
                                </button>
                                <button
                                    type="button"
                                    data-modal-open="deleteCategoryModal"
                                    data-form-action="{{ route('categories.destroy', $category->id) }}"
                                    data-delete-name="{{ $category->name }}"
                                    class="block w-full rounded-md px-3 py-2 text-left text-rose-600 hover:bg-rose-50">
                                    Delete
                                </button>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="mt-6">{{ $categories->links() }}</div>

<div id="deleteCategoryModal" data-modal class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/60 p-4">
    <div class="w-full max-w-sm overflow-hidden rounded-xl bg-white shadow-xl">
        <div class="p-5">
            <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-full bg-rose-100 text-lg font-bold text-rose-600">!</div>
            <h2 class="text-lg font-semibold text-slate-900">Delete category?</h2>
            <p class="mt-2 text-sm leading-6 text-slate-600">Are you sure you want to delete <span data-modal-field="deleteName" class="font-semibold text-slate-900"></span>? This action cannot be undone.</p>
        </div>
        <form action="{{ route('categories.destroy', 0) }}" method="POST" class="flex justify-end gap-2 border-t border-slate-200 bg-slate-50 px-5 py-4">
            @csrf
            @method('DELETE')
            <button type="button" data-modal-close class="rounded-md border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-white">Cancel</button>
            <button type="submit" class="rounded-md bg-rose-600 px-4 py-2 text-sm font-medium text-white hover:bg-rose-700">Delete</button>
        </form>
    </div>
</div>

<div id="addCategoryModal" data-modal class="fixed inset-0 z-50 {{ $errors->any() && old('form_type') !== 'edit_category' ? 'flex' : 'hidden' }} items-center justify-center bg-slate-950/60 p-4">
    <div class="w-full max-w-md overflow-hidden rounded-xl bg-white shadow-xl">
        <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
            <h2 class="text-lg font-semibold text-slate-900">Add Category</h2>
            <button type="button" data-modal-close class="rounded-md px-2 py-1 text-xl text-slate-400 hover:bg-slate-100">&times;</button>
        </div>
        <form action="{{ route('categories.store') }}" method="POST" class="space-y-4 p-5">
            @csrf
            <input type="hidden" name="form_type" value="add_category">
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700" for="add_category_name">Name</label>
                <input id="add_category_name" name="name" value="{{ old('form_type') === 'add_category' ? old('name') : '' }}" required class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500">
                @if(old('form_type') !== 'edit_category')
                    @error('name') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                @endif
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700" for="add_category_description">Description</label>
                <textarea id="add_category_description" name="description" class="min-h-24 w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500">{{ old('form_type') === 'add_category' ? old('description') : '' }}</textarea>
            </div>
            <div class="flex justify-end gap-2 border-t border-slate-200 pt-4">
                <button type="button" data-modal-close class="rounded-md border border-slate-300 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Cancel</button>
                <button type="submit" class="rounded-md bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-700">Add Category</button>
            </div>
        </form>
    </div>
</div>

<div id="editCategoryModal" data-modal class="fixed inset-0 z-50 {{ $errors->any() && old('form_type') === 'edit_category' ? 'flex' : 'hidden' }} items-center justify-center bg-slate-950/60 p-4">
    <div class="w-full max-w-md overflow-hidden rounded-xl bg-white shadow-xl">
        <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
            <h2 class="text-lg font-semibold text-slate-900">Edit Category</h2>
            <button type="button" data-modal-close class="rounded-md px-2 py-1 text-xl text-slate-400 hover:bg-slate-100">&times;</button>
        </div>
        <form action="{{ route('categories.update', old('edit_category_id', 0)) }}" method="POST" class="space-y-4 p-5">
            @csrf
            @method('PUT')
            <input type="hidden" name="form_type" value="edit_category">
            <input type="hidden" name="edit_category_id" value="{{ old('edit_category_id') }}" data-modal-field="editCategoryId">
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700" for="edit_category_name">Name</label>
                <input data-modal-field="name" id="edit_category_name" name="name" value="{{ old('name') }}" required class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500">
                @if(old('form_type') === 'edit_category')
                    @error('name') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                @endif
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700" for="edit_category_description">Description</label>
                <textarea data-modal-field="description" id="edit_category_description" name="description" class="min-h-24 w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500">{{ old('description') }}</textarea>
            </div>
            <div class="flex justify-end gap-2 border-t border-slate-200 pt-4">
                <button type="button" data-modal-close class="rounded-md border border-slate-300 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Cancel</button>
                <button type="submit" class="rounded-md bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-700">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection
