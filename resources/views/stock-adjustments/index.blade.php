@extends('layouts.app')

@section('content')
<div class="mb-7 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    <div>
        <p class="mb-2 text-xs font-bold uppercase tracking-[0.18em] text-sky-600">Inventory activity</p>
        <h1 class="text-3xl font-bold tracking-tight text-slate-950">Stock Adjustments</h1>
        <p class="mt-2 text-sm text-slate-500">Review manual changes made to recorded stock quantities.</p>
    </div>
    <div class="inline-flex items-center gap-2 self-start rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm sm:self-auto">
        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-sky-50 text-sky-700">
            <svg aria-hidden="true" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 5v14m-7-7h14" /></svg>
        </span>
        {{ number_format($adjustments->total()) }} {{ \Illuminate\Support\Str::plural('adjustment', $adjustments->total()) }}
    </div>
</div>

<section class="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6" aria-labelledby="stock-adjustment-filters-heading">
    <div class="mb-5 flex flex-wrap items-center justify-between gap-2">
        <div>
            <h2 id="stock-adjustment-filters-heading" class="text-base font-semibold text-slate-900">Find adjustments</h2>
            <p class="mt-1 text-sm text-slate-500">Search by product, SKU, or remarks, then filter by reason and date range.</p>
        </div>
        <span id="stock-adjustment-result-count" class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">{{ $adjustments->total() }} {{ \Illuminate\Support\Str::plural('result', $adjustments->total()) }}</span>
    </div>

    <form id="stock-adjustment-filters-form" action="{{ route('stock-adjustments.index') }}" method="GET" class="grid gap-3 sm:grid-cols-2 xl:grid-cols-[minmax(0,1fr)_minmax(12rem,0.4fr)_minmax(10rem,0.35fr)_minmax(10rem,0.35fr)_auto] sm:items-end">
        <div>
            <label for="stock-adjustment-search" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500">Search</label>
            <input id="stock-adjustment-search" type="search" name="search" value="{{ $search }}" placeholder="Product, SKU, or remarks" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-sky-500 focus:ring-2 focus:ring-sky-100">
        </div>
        <div>
            <label for="stock-adjustment-reason" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500">Reason</label>
            <select name="reason" id="stock-adjustment-reason" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-700 shadow-sm outline-none transition focus:border-sky-500 focus:ring-2 focus:ring-sky-100">
                <option value="">All reasons</option>
                @foreach ($reasons as $adjustmentReason)
                    <option value="{{ $adjustmentReason }}" @selected($reason === $adjustmentReason)>{{ $adjustmentReason }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="stock-adjustment-start-date" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500">Start date</label>
            <input id="stock-adjustment-start-date" type="date" name="start_date" value="{{ $startDate }}" max="{{ now()->toDateString() }}" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-700 shadow-sm outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-100">
        </div>
        <div>
            <label for="stock-adjustment-end-date" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500">End date</label>
            <input id="stock-adjustment-end-date" type="date" name="end_date" value="{{ $endDate }}" max="{{ now()->toDateString() }}" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-700 shadow-sm outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-100">
        </div>
        <div class="flex gap-2">
            <a href="{{ route('stock-adjustments.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-400 focus-visible:ring-offset-2">Clear</a>
        </div>
    </form>
</section>

<section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
        <div>
            <h2 class="text-base font-semibold text-slate-950">Adjustment history</h2>
            <p class="mt-1 text-sm text-slate-500">Newest adjustments are listed first.</p>
        </div>
        <span class="inline-flex w-fit items-center gap-2 rounded-full bg-slate-50 px-3 py-1.5 text-xs font-semibold text-slate-600 ring-1 ring-inset ring-slate-200">Page {{ $adjustments->currentPage() }} of {{ max($adjustments->lastPage(), 1) }}</span>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-[900px] w-full divide-y divide-slate-200 text-left text-sm">
            <thead class="bg-slate-50/80 text-[11px] uppercase tracking-[0.12em] text-slate-500">
                <tr>
                    <th scope="col" class="px-4 py-3.5 font-semibold">Product</th>
                    <th scope="col" class="px-4 py-3.5 font-semibold">SKU</th>
                    <th scope="col" class="px-4 py-3.5 text-right font-semibold">Previous</th>
                    <th scope="col" class="px-4 py-3.5 text-right font-semibold">New</th>
                    <th scope="col" class="px-4 py-3.5 text-right font-semibold">Change</th>
                    <th scope="col" class="px-4 py-3.5 font-semibold">Reason</th>
                    <th scope="col" class="px-4 py-3.5 font-semibold">Created at</th>
                </tr>
            </thead>
            <tbody id="stock-adjustment-results-body" class="divide-y divide-slate-100 bg-white">
                @forelse ($adjustments as $adjustment)
                    @php($difference = $adjustment->new_quantity - $adjustment->old_quantity)
                    <tr class="transition-colors hover:bg-sky-50/50">
                        <td class="max-w-56 truncate px-4 py-3.5 font-semibold text-slate-900" title="{{ $adjustment->product?->name ?? 'Product unavailable' }}">{{ $adjustment->product?->name ?? 'Product unavailable' }}</td>
                        <td class="max-w-36 truncate px-4 py-3.5 font-mono text-xs text-slate-500">{{ $adjustment->product?->sku ?? 'N/A' }}</td>
                        <td class="px-4 py-3.5 text-right font-semibold text-slate-700">{{ number_format($adjustment->old_quantity) }}</td>
                        <td class="px-4 py-3.5 text-right font-semibold text-slate-900">{{ number_format($adjustment->new_quantity) }}</td>
                        <td class="px-4 py-3.5 text-right font-semibold {{ $difference >= 0 ? 'text-emerald-700' : 'text-rose-700' }}">{{ $difference > 0 ? '+' : '' }}{{ number_format($difference) }}</td>
                        <td class="px-4 py-3.5"><span class="inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">{{ $adjustment->reason }}</span></td>
                        <td class="px-4 py-3.5 text-xs"><span class="block font-medium text-slate-700">{{ $adjustment->created_at?->format('M d, Y') }}</span><span class="mt-0.5 block text-slate-400">{{ $adjustment->created_at?->format('h:i A') }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-6 py-16 text-center"><p class="text-base font-semibold text-slate-900">No stock adjustments found</p><p class="mt-1 text-sm text-slate-500">Try a different search or reason.</p></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

<div id="stock-adjustment-pagination" class="mt-6">{{ $adjustments->links() }}</div>
@endsection
