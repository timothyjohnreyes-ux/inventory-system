@extends('layouts.app')

@section('content')

<h1 class="mb-2 text-3xl font-bold tracking-tight text-slate-900">Edit Category</h1>

<br>

<a class="mb-8 inline-flex text-sm font-semibold text-sky-700 transition hover:text-sky-900" href="{{ route('categories.index') }}">
    Back to Categories
</a>

<form class="mt-6 max-w-2xl space-y-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8" action="{{ route('categories.update', $category->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700" for="name">Category Name</label>
        <input class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm outline-none transition focus:border-amber-500 focus:ring-2 focus:ring-amber-200" type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required>

        @error('name')
        <p class="mt-2 text-sm font-medium text-rose-600">{{ $message }}</p>
        @enderror

    </div>

    <br>

    <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700" for="description">Description</label>
        <textarea class="min-h-32 w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm outline-none transition focus:border-amber-500 focus:ring-2 focus:ring-amber-200" name="description" id="description">{{ old('description', $category->description) }}</textarea>

        @error('description')
        <p class="mt-2 text-sm font-medium text-rose-600">{{ $message }}</p>
        @enderror

    </div>

    <br>

    <button class="rounded-xl bg-amber-500 px-5 py-3 text-sm font-semibold text-slate-950 shadow-sm transition hover:bg-amber-400 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:ring-offset-2" type="submit">Update Category</button>

</form>

@endsection