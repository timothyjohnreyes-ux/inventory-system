@extends('layouts.app')

@section('content')
<div class="mb-7 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    <div>
        <p class="mb-2 text-xs font-bold uppercase tracking-[0.18em] text-sky-600">Catalog</p>
        <h1 class="text-3xl font-bold tracking-tight text-slate-950">Categories</h1>
        <p class="mt-2 text-sm text-slate-500">Group related products so inventory stays easy to browse.</p>
    </div>
    <button type="button" data-modal-open="addCategoryModal" class="inline-flex w-fit items-center gap-2 rounded-xl bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm shadow-sky-600/20 transition hover:bg-sky-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-sky-500 focus-visible:ring-offset-2">
        <svg aria-hidden="true" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m-7-7h14" /></svg>
        Add category
    </button>
</div>

<section class="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6" aria-labelledby="category-filters-heading">
    <div class="mb-5 flex flex-wrap items-center justify-between gap-2">
        <div>
            <h2 id="category-filters-heading" class="text-base font-semibold text-slate-900">Find categories</h2>
            <p class="mt-1 text-sm text-slate-500">Search by category name or description, or sort the list.</p>
        </div>
        <span id="category-result-count" class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">{{ $categories->total() }} {{ \Illuminate\Support\Str::plural('category', $categories->total()) }}</span>
    </div>

    <form id="category-filters-form" action="{{ route('categories.index') }}" method="GET" class="grid gap-3 sm:grid-cols-[minmax(0,1fr)_minmax(12rem,0.4fr)_auto] sm:items-end">
        <div>
            <label for="category-search" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500">Search</label>
            <input id="category-search" type="search" name="search" value="{{ $search }}" placeholder="Category name or description" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-sky-500 focus:ring-2 focus:ring-sky-100">
        </div>
        <div>
            <label for="category-sort" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500">Sort by</label>
            <select name="sort" id="category-sort" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-700 shadow-sm outline-none transition focus:border-sky-500 focus:ring-2 focus:ring-sky-100">
                <option value="newest" @selected($sort === 'newest')>Newest first</option>
                <option value="oldest" @selected($sort === 'oldest')>Oldest first</option>
                <option value="name_asc" @selected($sort === 'name_asc')>Name: A-Z</option>
                <option value="name_desc" @selected($sort === 'name_desc')>Name: Z-A</option>
            </select>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('categories.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-400 focus-visible:ring-offset-2">Clear</a>
        </div>
    </form>
</section>

<div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="flex items-center justify-between gap-3 border-b border-slate-100 px-5 py-4 sm:px-6">
        <div>
            <h2 class="text-sm font-semibold text-slate-900">All categories</h2>
            <p class="mt-0.5 text-xs text-slate-500">Browse and organize your product groups.</p>
        </div>
        <span class="rounded-full bg-slate-50 px-3 py-1.5 text-xs font-semibold text-slate-600 ring-1 ring-inset ring-slate-200">{{ number_format($categories->total()) }}</span>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-[560px] w-full table-fixed divide-y divide-slate-200 text-left text-sm">
        <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
            <tr>
                <th scope="col" class="w-14 px-4 py-3.5 font-semibold">ID</th>
                <th scope="col" class="w-1/4 px-4 py-3.5 font-semibold">Name</th>
                <th scope="col" class="px-4 py-3.5 font-semibold">Description</th>
                <th scope="col" class="w-28 px-4 py-3.5 font-semibold">Actions</th>
            </tr>
        </thead>
        <tbody id="category-results-body" class="divide-y divide-slate-100">
            @forelse ($categories as $category)
                <tr class="transition-colors hover:bg-sky-50/60">
                    <td class="whitespace-nowrap px-4 py-3.5 text-xs font-semibold text-slate-400">#{{ $category->id }}</td>
                    <td class="max-w-64 truncate px-4 py-3.5 font-semibold text-slate-900" title="{{ $category->name }}">{{ $category->name }}</td>
                    <td class="max-w-lg truncate px-4 py-3.5 text-slate-600" title="{{ $category->description }}">
                        @if (filled($category->description))
                            {{ $category->description }}
                        @else
                            &mdash;
                        @endif
                    </td>
                    <td class="whitespace-nowrap px-4 py-3.5">
                        <div class="flex items-center gap-1">
                            <a href="{{ route('categories.show', $category->id) }}" aria-label="View {{ $category->name }}" title="View category" class="inline-flex h-9 w-9 items-center justify-center rounded-xl text-sky-700 transition hover:bg-sky-50 hover:text-sky-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-sky-400">
                                <svg aria-hidden="true" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                            </a>
                            <button type="button" data-modal-open="editCategoryModal" data-form-action="{{ route('categories.update', $category->id) }}" data-edit-category-id="{{ $category->id }}" data-name="{{ $category->name }}" data-description="{{ $category->description }}" aria-label="Edit {{ $category->name }}" title="Edit category" class="inline-flex h-9 w-9 items-center justify-center rounded-xl text-slate-500 transition hover:bg-slate-100 hover:text-slate-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-400">
                                <svg aria-hidden="true" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m16.862 4.487 1.688-1.688a2.121 2.121 0 0 1 3 3l-9.193 9.193-4.125 1.125 1.125-4.125 7.505-7.505Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19.5 7.5 16.5 4.5" />
                                </svg>
                            </button>
                            {{-- Delete category action temporarily disabled.
                            <button type="button" data-modal-open="deleteCategoryModal" data-form-action="{{ route('categories.destroy', $category->id) }}" data-delete-name="{{ $category->name }}" aria-label="Delete {{ $category->name }}" title="Delete category" class="inline-flex h-9 w-9 items-center justify-center rounded-xl text-rose-600 transition hover:bg-rose-50 hover:text-rose-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-rose-400">
                                <svg aria-hidden="true" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 6h18M8 6V4h8v2m-9 0 1 14h8l1-14M10 10v6m4-6v6" />
                                </svg>
                            </button>
                            --}}
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-6 py-16 text-center">
                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                            <svg aria-hidden="true" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 4.75A1.75 1.75 0 0 1 5.75 3h4.5A1.75 1.75 0 0 1 12 4.75v4.5A1.75 1.75 0 0 1 10.25 11h-4.5A1.75 1.75 0 0 1 4 9.25v-4.5Zm8 10A1.75 1.75 0 0 1 13.75 13h4.5A1.75 1.75 0 0 1 20 14.75v4.5A1.75 1.75 0 0 1 18.25 21h-4.5A1.75 1.75 0 0 1 12 19.25v-4.5Zm0-10A1.75 1.75 0 0 1 13.75 3h4.5A1.75 1.75 0 0 1 20 4.75v4.5A1.75 1.75 0 0 1 18.25 11h-4.5A1.75 1.75 0 0 1 12 9.25v-4.5Zm-8 10A1.75 1.75 0 0 1 5.75 13h4.5A1.75 1.75 0 0 1 12 14.75v4.5A1.75 1.75 0 0 1 10.25 21h-4.5A1.75 1.75 0 0 1 4 19.25v-4.5Z" /></svg>
                        </div>
                        <p class="mt-4 text-base font-semibold text-slate-900">No categories yet</p>
                        <p class="mt-1 text-sm text-slate-500">Add your first category to start organizing products.</p>
                        <button type="button" data-modal-open="addCategoryModal" class="mt-4 inline-flex items-center rounded-xl bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-sky-700">Add category</button>
                    </td>
                </tr>
            @endforelse
        </tbody>
        </table>
    </div>
</div>

<div id="category-pagination" class="mt-6 {{ $categories->hasPages() ? '' : 'hidden' }}">{{ $categories->links() }}</div>

<div id="addCategoryModal" data-modal class="fixed inset-0 z-50 {{ $errors->any() && old('form_type') !== 'edit_category' ? 'flex' : 'hidden' }} items-center justify-center bg-slate-950/60 p-4">
    <div class="w-full max-w-md overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl shadow-slate-900/20">
        <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
            <h2 class="text-lg font-semibold text-slate-900">Add Category</h2>
            <button type="button" data-modal-close aria-label="Close add category dialog" class="rounded-xl p-2 text-xl text-slate-400 transition hover:bg-slate-100 hover:text-slate-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-sky-500">&times;</button>
        </div>
        <form action="{{ route('categories.store') }}" method="POST" class="space-y-4 p-5">
            @csrf
            <input type="hidden" name="form_type" value="add_category">
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700" for="add_category_name">Name</label>
                <input id="add_category_name" name="name" value="{{ old('form_type') === 'add_category' ? old('name') : '' }}" required class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition focus:border-sky-500 focus:ring-2 focus:ring-sky-100">
                @if(old('form_type') !== 'edit_category')
                    @error('name') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                @endif
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700" for="add_category_description">Description</label>
                <textarea id="add_category_description" name="description" class="min-h-24 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition focus:border-sky-500 focus:ring-2 focus:ring-sky-100">{{ old('form_type') === 'add_category' ? old('description') : '' }}</textarea>
            </div>
            <div class="flex justify-end gap-2 border-t border-slate-200 pt-4">
                <button type="button" data-modal-close class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Cancel</button>
                <button type="submit" class="rounded-xl bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-sky-700">Add category</button>
            </div>
        </form>
    </div>
</div>

<div id="editCategoryModal" data-modal class="fixed inset-0 z-50 {{ $errors->any() && old('form_type') === 'edit_category' ? 'flex' : 'hidden' }} items-center justify-center bg-slate-950/60 p-4">
    <div class="w-full max-w-md overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl shadow-slate-900/20">
        <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
            <h2 class="text-lg font-semibold text-slate-900">Edit Category</h2>
            <button type="button" data-modal-close aria-label="Close edit category dialog" class="rounded-xl p-2 text-xl text-slate-400 transition hover:bg-slate-100 hover:text-slate-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-sky-500">&times;</button>
        </div>
        <form action="{{ route('categories.update', old('edit_category_id', 0)) }}" method="POST" class="space-y-4 p-5">
            @csrf
            @method('PUT')
            <input type="hidden" name="form_type" value="edit_category">
            <input type="hidden" name="edit_category_id" value="{{ old('edit_category_id') }}" data-modal-field="editCategoryId">
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700" for="edit_category_name">Name</label>
                <input data-modal-field="name" id="edit_category_name" name="name" value="{{ old('name') }}" required class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition focus:border-sky-500 focus:ring-2 focus:ring-sky-100">
                @if(old('form_type') === 'edit_category')
                    @error('name') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                @endif
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700" for="edit_category_description">Description</label>
                <textarea data-modal-field="description" id="edit_category_description" name="description" class="min-h-24 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition focus:border-sky-500 focus:ring-2 focus:ring-sky-100">{{ old('description') }}</textarea>
            </div>
            <div class="flex justify-end gap-2 border-t border-slate-200 pt-4">
                <button type="button" data-modal-close class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Cancel</button>
                <button type="submit" class="rounded-xl bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-sky-700">Save changes</button>
            </div>
        </form>
    </div>
</div>
@endsection
