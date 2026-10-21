@extends('layouts.app')

@section('content')
<div class="mb-7 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    <div>
        <p class="mb-2 text-xs font-bold uppercase tracking-[0.18em] text-sky-600">Catalog</p>
        <h1 class="text-3xl font-bold tracking-tight text-slate-950">Add category</h1>
        <p class="mt-2 text-sm text-slate-500">Create a category to keep related products organized.</p>
    </div>
    <a href="{{ route('categories.index') }}" class="inline-flex items-center gap-2 self-start rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:bg-slate-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-sky-500 focus-visible:ring-offset-2 sm:self-auto">
        <svg aria-hidden="true" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 12H5m7 7-7-7 7-7" /></svg>
        Back to categories
    </a>
</div>

<form class="max-w-3xl overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm" action="{{ route('categories.store') }}" method="POST">
    @csrf
    <div class="space-y-5 p-5 sm:p-7">
        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700" for="name">Category name <span class="text-rose-500">*</span></label>
            <input class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-sky-500 focus:ring-4 focus:ring-sky-100" type="text" name="name" id="name" value="{{ old('name') }}" required autofocus autocomplete="off" placeholder="e.g. Accessories">
            @error('name')
                <p class="mt-2 text-sm font-medium text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700" for="description">Description <span class="font-normal text-slate-400">(optional)</span></label>
            <textarea id="description" name="description" class="min-h-32 w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-sky-500 focus:ring-4 focus:ring-sky-100" placeholder="Describe the products in this category">{{ old('description') }}</textarea>
            @error('description')
                <p class="mt-2 text-sm font-medium text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="flex flex-col-reverse gap-3 border-t border-slate-100 bg-slate-50/70 px-5 py-4 sm:flex-row sm:justify-end sm:px-7">
        <a href="{{ route('categories.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-400 focus-visible:ring-offset-2">Cancel</a>
        <button class="inline-flex items-center justify-center gap-2 rounded-xl bg-sky-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-sky-500 focus-visible:ring-offset-2" type="submit">
            <svg aria-hidden="true" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m-7-7h14" /></svg>
            Save category
        </button>
    </div>
</form>
@endsection
