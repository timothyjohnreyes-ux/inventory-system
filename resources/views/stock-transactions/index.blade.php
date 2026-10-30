@extends('layouts.app')

@section('content')
<div class="mb-7 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    <div>
        <p class="mb-2 text-xs font-bold uppercase tracking-[0.18em] text-sky-600">Inventory activity</p>
        <h1 class="text-3xl font-bold tracking-tight text-slate-950">Stock history</h1>
        <p class="mt-2 text-sm text-slate-500">Review incoming and outgoing stock movements.</p>
    </div>
    <div class="inline-flex items-center gap-2 self-start rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm sm:self-auto">
        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-sky-50 text-sky-700">
            <svg aria-hidden="true" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6.75A2.75 2.75 0 0 1 6.75 4h10.5A2.75 2.75 0 0 1 20 6.75v10.5A2.75 2.75 0 0 1 17.25 20H6.75A2.75 2.75 0 0 1 4 17.25V6.75ZM8 8h8m-8 4h8m-8 4h4" /></svg>
        </span>
        {{ number_format($transactions->total()) }} {{ \Illuminate\Support\Str::plural('transaction', $transactions->total()) }}
    </div>
</div>

<section class="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6" aria-labelledby="stock-filters-heading">
    <div class="mb-5 flex flex-wrap items-center justify-between gap-2">
        <div>
            <h2 id="stock-filters-heading" class="text-base font-semibold text-slate-900">Find transactions</h2>
            <p class="mt-1 text-sm text-slate-500">Search by product, SKU, or remarks, then filter by movement type.</p>
        </div>
        <span id="stock-result-count" class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">{{ $transactions->total() }} {{ \Illuminate\Support\Str::plural('result', $transactions->total()) }}</span>
    </div>

    <form id="stock-filters-form" action="{{ route('stock-transactions.index') }}" method="GET" class="grid gap-3 sm:grid-cols-[minmax(0,1fr)_minmax(12rem,0.4fr)_auto] sm:items-end">
        <div>
            <label for="stock-search" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500">Search</label>
            <input id="stock-search" type="search" name="search" value="{{ $search }}" placeholder="Product, SKU, or remarks" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-sky-500 focus:ring-2 focus:ring-sky-100">
        </div>
        <div>
            <label for="stock-type" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500">Movement</label>
            <select name="type" id="stock-type" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-700 shadow-sm outline-none transition focus:border-sky-500 focus:ring-2 focus:ring-sky-100">
                <option value="">All movements</option>
                <option value="in" @selected($type === 'in')>Stock in</option>
                <option value="out" @selected($type === 'out')>Stock out</option>
            </select>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('stock-transactions.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-400 focus-visible:ring-offset-2">Clear</a>
        </div>
    </form>
</section>

<section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm" aria-labelledby="stock-history-heading">
    <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
        <div>
            <h2 id="stock-history-heading" class="text-base font-semibold text-slate-950">Transactions</h2>
            <p class="mt-1 text-sm text-slate-500">Newest activity is listed first.</p>
        </div>
        <span class="inline-flex w-fit items-center gap-2 rounded-full bg-slate-50 px-3 py-1.5 text-xs font-semibold text-slate-600 ring-1 ring-inset ring-slate-200">
            <span class="h-1.5 w-1.5 rounded-full bg-sky-500"></span>
            Page {{ $transactions->currentPage() }} of {{ max($transactions->lastPage(), 1) }}
        </span>
    </div>

    <div>
        <table class="w-full table-fixed divide-y divide-slate-200 text-left text-xs sm:text-sm">
            <colgroup>
                <col class="w-[9%]">
                <col class="w-[18%]">
                <col class="w-[11%]">
                <col class="w-[12%]">
                <col class="w-[10%]">
                <col class="w-[13%]">
                <col class="w-[27%]">
            </colgroup>
            <thead class="bg-slate-50/80 text-[10px] uppercase tracking-wide text-slate-500 sm:text-[11px] sm:tracking-[0.12em]">
                <tr>
                    <th scope="col" class="px-1 py-2 font-semibold sm:px-2 sm:py-3.5">ID</th>
                    <th scope="col" class="px-1 py-2 font-semibold sm:px-2 sm:py-3.5">Product</th>
                    <th scope="col" class="px-1 py-2 font-semibold sm:px-2 sm:py-3.5">SKU</th>
                    <th scope="col" class="px-1 py-2 font-semibold sm:px-2 sm:py-3.5">Move</th>
                    <th scope="col" class="px-1 py-2 text-right font-semibold sm:px-2 sm:py-3.5">Qty</th>
                    <th scope="col" class="px-1 py-2 font-semibold sm:px-2 sm:py-3.5">Remarks</th>
                    <th scope="col" class="px-1 py-2 font-semibold sm:px-2 sm:py-3.5">Created at</th>
                </tr>
            </thead>
            <tbody id="stock-results-body" class="divide-y divide-slate-100 bg-white">
                @forelse ($transactions as $transaction)
                    <tr class="transition-colors hover:bg-sky-50/50">
                        <td class="overflow-hidden px-1 py-2 sm:px-2 sm:py-3.5">
                            <span class="font-mono text-[10px] font-semibold text-slate-600 sm:text-xs">#{{ $transaction->id }}</span>
                        </td>
                        <td class="overflow-hidden px-1 py-2 font-semibold text-slate-900 sm:px-2 sm:py-3.5" title="{{ $transaction->product?->name ?? 'Product unavailable' }}">
                            <span class="block truncate">{{ $transaction->product?->name ?? 'Product unavailable' }}</span>
                        </td>
                        <td class="overflow-hidden px-1 py-2 font-mono text-[10px] text-slate-500 sm:px-2 sm:py-3.5 sm:text-xs" title="{{ $transaction->product?->sku ?? 'N/A' }}">
                            <span class="block truncate">{{ $transaction->product?->sku ?? 'N/A' }}</span>
                        </td>
                        <td class="overflow-hidden px-1 py-2 sm:px-2 sm:py-3.5">
                            @if ($transaction->type === 'in')
                                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-1 py-1 text-[10px] font-semibold text-emerald-700 ring-1 ring-inset ring-emerald-200 sm:gap-1.5 sm:px-2.5 sm:text-xs">
                                    <svg aria-hidden="true" class="h-3 w-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m-7-7h14" /></svg>
                                    <span class="sm:hidden">In</span><span class="hidden sm:inline">Stock in</span>
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 rounded-full bg-orange-50 px-1 py-1 text-[10px] font-semibold text-orange-700 ring-1 ring-inset ring-orange-200 sm:gap-1.5 sm:px-2.5 sm:text-xs">
                                    <svg aria-hidden="true" class="h-3 w-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14" /></svg>
                                    <span class="sm:hidden">Out</span><span class="hidden sm:inline">Stock out</span>
                                </span>
                            @endif
                        </td>
                        <td class="overflow-hidden px-1 py-2 text-right font-bold text-[10px] sm:px-2 sm:py-3.5 sm:text-sm {{ $transaction->type === 'in' ? 'text-emerald-700' : 'text-orange-700' }}">
                            @if ($transaction->type === 'in')
                                +{{ number_format($transaction->quantity) }}
                            @else
                                &minus;{{ number_format($transaction->quantity) }}
                            @endif
                        </td>
                        <td class="overflow-hidden px-1 py-2 text-slate-600 sm:px-2 sm:py-3.5" title="{{ $transaction->remarks ?? '' }}">
                            <span class="block truncate">
                                @if (filled($transaction->remarks))
                                    {{ $transaction->remarks }}
                                @else
                                    &mdash;
                                @endif
                            </span>
                        </td>
                        <td class="px-1 py-2 text-[10px] sm:px-2 sm:py-3.5 sm:text-xs">
                            <span class="block font-medium leading-tight text-slate-700">{{ $transaction->created_at->format('M d, Y') }}</span>
                            <span class="mt-0.5 block leading-tight text-slate-400">{{ $transaction->created_at->format('h:i A') }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                                <svg aria-hidden="true" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6.75A2.75 2.75 0 0 1 6.75 4h10.5A2.75 2.75 0 0 1 20 6.75v10.5A2.75 2.75 0 0 1 17.25 20H6.75A2.75 2.75 0 0 1 4 17.25V6.75ZM8 8h8m-8 4h8m-8 4h4" /></svg>
                            </div>
                            <p class="mt-4 text-base font-semibold text-slate-900">No stock movements yet</p>
                            <p class="mt-1 text-sm text-slate-500">Stock in and stock out activity will appear here.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

<div id="stock-pagination" class="mt-6 {{ $transactions->hasPages() ? '' : 'hidden' }}">{{ $transactions->links() }}</div>
@endsection
