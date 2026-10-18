@extends('layouts.app')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-semibold text-slate-900">Add New Category</h1>
    <a class="mt-2 inline-flex text-sm text-slate-600 hover:text-slate-900" href="{{ route('categories.index') }}">
    Back to Categories
    </a>
</div>

<form class="max-w-2xl space-y-5 border border-slate-200 bg-white p-6" action="{{ route('categories.store') }}" method="POST">
    @csrf

    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700" for="name">Name</label>
        <input class="w-full rounded-md border border-slate-300 px-3 py-2 text-slate-900 outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500" type="text" name="name" id="name" value="{{ old('name') }}">

        @error('name')
        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700" for="description">Description</label>
        <textarea
            name="description"
            class="min-h-32 w-full rounded-md border border-slate-300 px-3 py-2 text-slate-900 outline-none placeholder:text-slate-400 focus:border-sky-500 focus:ring-1 focus:ring-sky-500"
            placeholder="Category description">{{ old('description') }}</textarea>

        @error('description')
        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <button class="rounded-md bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2" type="submit">Add Category</button>
</form>

@endsection