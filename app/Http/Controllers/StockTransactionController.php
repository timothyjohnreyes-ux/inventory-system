<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockTransaction;
use App\Services\ActivityLogger;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class StockTransactionController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        // Step 1: Validate the submitted data
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'type' => ['required', Rule::in(['in', 'out'])],
            'quantity' => ['required', 'integer', 'min:1'],
            'remarks' => ['nullable', 'string', 'max:1000'],
        ]);

        // Step 2: Process the stock movement safely
        $product = DB::transaction(function () use ($validated) {
            $product = Product::whereKey($validated['product_id'])
                ->lockForUpdate()
                ->firstOrFail();

            $quantity = (int) $validated['quantity'];

            if ($validated['type'] === 'in' && ! $product->is_active) {
                throw ValidationException::withMessages([
                    'quantity' => 'Stock in is not allowed for archived products.',
                ]);
            }

            // Step 3: Update the product quantity
            if ($validated['type'] === 'out') {
                if ($product->quantity < $quantity) {
                    throw ValidationException::withMessages([
                        'quantity' => 'Insufficient stock. Available quantity: '
                            .$product->quantity,
                    ]);
                }

                $product->decrement('quantity', $quantity);
            } else {
                $product->increment('quantity', $quantity);
            }

            // Step 4: Save the transaction history
            StockTransaction::create([
                'product_id' => $product->id,
                'type' => $validated['type'],
                'quantity' => $quantity,
                'remarks' => $validated['remarks'] ?? null,
            ]);

            return $product;
        });

        $action = $validated['type'] === 'in' ? 'stock_in' : 'stock_out';

        $label = $validated['type'] === 'in' ? 'Stock In' : 'Stock Out';

        $remarks = ! empty($validated['remarks'])
            ? " Remarks: {$validated['remarks']}"
            : '';

        ActivityLogger::log(
            $action,
            "{$label}: {$validated['quantity']} units of product {$product->name} (SKU: {$product->sku}).{$remarks}",
            'Stock'
        );

        // Step 5: Return a success message
        return redirect()
            ->route('products.index', [
                'status' => $request->input('return_status', 'active'),
            ])
            ->with('success', 'Stock transaction recorded successfully.');
    }

    public function index(Request $request): View
    {
        $request->session()->put(
            StockTransaction::LAST_VIEWED_SESSION_KEY,
            StockTransaction::max('id') ?? 0
        );

        $search = $request->input('search');
        $type = $request->input('type');
        $dateRules = [
            'start_date' => ['nullable', 'date_format:Y-m-d', 'before_or_equal:today'],
            'end_date' => ['nullable', 'date_format:Y-m-d', 'before_or_equal:today'],
        ];

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $dateRules['end_date'][] = 'after_or_equal:start_date';
        }

        $dateFilters = $request->validate($dateRules);
        $startDate = $dateFilters['start_date'] ?? '';
        $endDate = $dateFilters['end_date'] ?? '';

        if (! is_string($search)) {
            $search = null;
        }

        if (! in_array($type, ['in', 'out'], true)) {
            $type = null;
        }

        $transactions = StockTransaction::query()
            ->with('product')
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('remarks', 'like', "%{$search}%")
                        ->orWhereHas('product', function ($query) use ($search) {
                            $query->where('name', 'like', "%{$search}%")
                                ->orWhere('sku', 'like', "%{$search}%");
                        });
                });
            })
            ->when($type, function ($query, $type) {
                $query->where('type', $type);
            })
            ->when($dateFilters['start_date'] ?? null, function ($query, $startDate) {
                $query->whereDate('created_at', '>=', $startDate);
            })
            ->when($dateFilters['end_date'] ?? null, function ($query, $endDate) {
                $query->whereDate('created_at', '<=', $endDate);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('stock-transactions.index', compact(
            'transactions',
            'search',
            'type',
            'startDate',
            'endDate'
        ));
    }
}
