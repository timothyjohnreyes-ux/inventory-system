@extends('layouts.app')

@section('content')
<div class="mb-7 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    <div>
        <p class="mb-2 text-xs font-bold uppercase tracking-[0.18em] text-sky-600">System activity</p>
        <h1 class="text-3xl font-bold tracking-tight text-slate-950">Activity log</h1>
        <p class="mt-2 text-sm text-slate-500">Review changes and actions recorded across your inventory.</p>
    </div>
    <div class="inline-flex items-center gap-2 self-start rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm sm:self-auto">
        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-sky-50 text-sky-700">
            <svg aria-hidden="true" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6.75A2.75 2.75 0 0 1 6.75 4h10.5A2.75 2.75 0 0 1 20 6.75v10.5A2.75 2.75 0 0 1 17.25 20H6.75A2.75 2.75 0 0 1 4 17.25V6.75ZM8 8h8m-8 4h8m-8 4h4" /></svg>
        </span>
        {{ number_format($activityLogs->total()) }} {{ \Illuminate\Support\Str::plural('record', $activityLogs->total()) }}
    </div>
</div>

<section class="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6" aria-labelledby="activity-filters-heading">
    <div class="mb-5 flex flex-wrap items-center justify-between gap-2">
        <div>
            <h2 id="activity-filters-heading" class="text-base font-semibold text-slate-900">Find activity</h2>
            <p class="mt-1 text-sm text-slate-500">Search by action, description, user, or module.</p>
        </div>
        <span id="activity-result-count" class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">{{ $activityLogs->total() }} {{ \Illuminate\Support\Str::plural('result', $activityLogs->total()) }}</span>
    </div>

    <form id="activity-filters-form" action="{{ route('activity-logs.index') }}" method="GET" class="grid gap-3 sm:grid-cols-[minmax(0,1fr)_minmax(12rem,0.4fr)_auto] sm:items-end">
        <div>
            <label for="activity-search" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500">Search</label>
            <input id="activity-search" type="search" name="search" value="{{ request('search') }}" placeholder="Search activity..." class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-sky-500 focus:ring-2 focus:ring-sky-100">
        </div>
        <div>
            <label for="module" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500">Module</label>
            <select name="module" id="module" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-700 shadow-sm outline-none transition focus:border-sky-500 focus:ring-2 focus:ring-sky-100">
                <option value="">All modules</option>
                @foreach ($modules as $module)
                    <option value="{{ $module }}" @selected(request('module') === $module)>{{ $module }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('activity-logs.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-400 focus-visible:ring-offset-2">Clear</a>
        </div>
    </form>
</section>

<section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm" aria-labelledby="activity-log-heading">
    <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
        <div>
            <h2 id="activity-log-heading" class="text-base font-semibold text-slate-950">Recorded activity</h2>
            <p class="mt-1 text-sm text-slate-500">Newest activity is listed first.</p>
        </div>
        <span class="inline-flex w-fit items-center gap-2 rounded-full bg-slate-50 px-3 py-1.5 text-xs font-semibold text-slate-600 ring-1 ring-inset ring-slate-200">
            <span class="h-1.5 w-1.5 rounded-full bg-sky-500"></span>
            Page {{ $activityLogs->currentPage() }} of {{ max($activityLogs->lastPage(), 1) }}
        </span>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-[760px] w-full table-fixed divide-y divide-slate-200 text-left text-sm">
            <colgroup>
                <col class="w-16">
                <col class="w-40">
                <col class="w-32">
                <col>
                <col class="w-32">
                <col class="w-40">
            </colgroup>
            <thead class="bg-slate-50/80 text-[11px] uppercase tracking-[0.12em] text-slate-500">
                <tr>
                    <th scope="col" class="px-4 py-3.5 font-semibold">ID</th>
                    <th scope="col" class="px-4 py-3.5 font-semibold">User</th>
                    <th scope="col" class="px-4 py-3.5 font-semibold">Action</th>
                    <th scope="col" class="px-4 py-3.5 font-semibold">Description</th>
                    <th scope="col" class="px-4 py-3.5 font-semibold">Module</th>
                    <th scope="col" class="px-4 py-3.5 font-semibold">Created at</th>
                </tr>
            </thead>
            <tbody id="activity-results-body" class="divide-y divide-slate-100 bg-white">
                @forelse ($activityLogs as $log)
                    <tr class="transition-colors hover:bg-sky-50/50">
                        <td class="whitespace-nowrap px-4 py-3.5 font-mono text-xs font-semibold text-slate-500">#{{ $log->id }}</td>
                        <td class="max-w-40 truncate px-4 py-3.5 font-medium text-slate-700" title="{{ $log->user_name ?? 'System / Unknown' }}">{{ $log->user_name ?? 'System / Unknown' }}</td>
                        <td class="px-4 py-3.5">
                            <span class="inline-flex rounded-full bg-sky-50 px-2.5 py-1 text-xs font-semibold capitalize text-sky-700 ring-1 ring-inset ring-sky-200">{{ $log->action }}</span>
                        </td>
                        <td class="max-w-md truncate px-4 py-3.5 text-slate-600" title="{{ $log->description }}">{{ $log->description }}</td>
                        <td class="px-4 py-3.5">
                            <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">{{ $log->module ?? 'N/A' }}</span>
                        </td>
                        <td class="px-4 py-3.5 text-xs">
                            <span class="block font-medium leading-tight text-slate-700">{{ $log->created_at?->format('M d, Y') }}</span>
                            <span class="mt-0.5 block leading-tight text-slate-400">{{ $log->created_at?->format('h:i A') }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                                <svg aria-hidden="true" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6.75A2.75 2.75 0 0 1 6.75 4h10.5A2.75 2.75 0 0 1 20 6.75v10.5A2.75 2.75 0 0 1 17.25 20H6.75A2.75 2.75 0 0 1 4 17.25V6.75ZM8 8h8m-8 4h8m-8 4h4" /></svg>
                            </div>
                            <p class="mt-4 text-base font-semibold text-slate-900">No activity records found</p>
                            <p class="mt-1 text-sm text-slate-500">Try adjusting your search or module filter.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

<div id="activity-pagination" class="mt-6 {{ $activityLogs->total() > 0 ? '' : 'hidden' }}">
    <div class="mb-3 text-center text-xs font-medium text-slate-500 sm:text-left">
        Showing {{ $activityLogs->firstItem() ?? 0 }} to {{ $activityLogs->lastItem() ?? 0 }} of {{ number_format($activityLogs->total()) }} activity records
    </div>
    <div class="flex justify-center overflow-x-auto sm:justify-end">
        @if ($activityLogs->hasPages())
            {{ $activityLogs->links() }}
        @else
            <span aria-current="page" class="relative inline-flex items-center rounded-lg bg-sky-600 px-3 py-2 text-sm font-semibold text-white shadow-sm">
                1
            </span>
        @endif
    </div>
</div>
@endsection
