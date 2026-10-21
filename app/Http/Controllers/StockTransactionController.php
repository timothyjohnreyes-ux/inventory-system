<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockTransaction;
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

        if (! $request->session()->has(StockTransaction::LAST_VIEWED_SESSION_KEY)) {
            $request->session()->put(
                StockTransaction::LAST_VIEWED_SESSION_KEY,
                StockTransaction::max('id') ?? 0
            );
        }

        // Step 2: Process the stock movement safely
        DB::transaction(function () use ($validated) {
            $product = Product::whereKey($validated['product_id'])
                ->lockForUpdate()
                ->firstOrFail();

            $quantity = (int) $validated['quantity'];

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
        });

        // Step 5: Return a success message
        return redirect()
            ->route('products.index')
            ->with('success', 'Stock transaction recorded successfully.');
    }

    public function index(Request $request): View
    {
        $request->session()->put(
            StockTransaction::LAST_VIEWED_SESSION_KEY,
            StockTransaction::max('id') ?? 0
        );

        $transactions = StockTransaction::with('product')
            ->latest()
            ->paginate(10);

        return view('stock-transactions.index', compact('transactions'));
    }
}
