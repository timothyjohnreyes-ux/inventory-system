<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockAdjustment;
use App\Services\ActivityLogger;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class StockAdjustmentController extends Controller
{
    public function index(Request $request): View
    {
        $request->session()->put(
            StockAdjustment::LAST_VIEWED_SESSION_KEY,
            StockAdjustment::max('id') ?? 0
        );

        $search = $request->input('search');
        $reason = $request->input('reason');
        $reasons = ['Physical Count', 'Damaged', 'Expired', 'Missing', 'Other'];

        if (! is_string($search)) {
            $search = null;
        }

        if (! in_array($reason, $reasons, true)) {
            $reason = null;
        }

        $adjustments = StockAdjustment::query()
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
            ->when($reason, function ($query, $reason) {
                $query->where('reason', $reason);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('stock-adjustments.index', compact(
            'adjustments',
            'search',
            'reason',
            'reasons'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => [
                'required',
                'exists:products,id',
            ],
            'new_quantity' => [
                'required',
                'integer',
                'min:0',
            ],
            'reason' => [
                'required',
                Rule::in([
                    'Physical Count',
                    'Damaged',
                    'Expired',
                    'Missing',
                    'Other',
                ]),
            ],
            'remarks' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        DB::transaction(function () use ($validated) {
            $product = Product::where('is_active', true)
                ->lockForUpdate()
                ->findOrFail($validated['product_id']);

            $oldQuantity = $product->quantity;
            $newQuantity = $validated['new_quantity'];

            if ($oldQuantity === $newQuantity) {
                throw ValidationException::withMessages([
                    'new_quantity' => 'The new quantity must be different from the current stock.',
                ]);
            }

            $product->update([
                'quantity' => $newQuantity,
            ]);

            StockAdjustment::create([
                'product_id' => $product->id,
                'old_quantity' => $oldQuantity,
                'new_quantity' => $newQuantity,
                'reason' => $validated['reason'],
                'remarks' => $validated['remarks'] ?? null,
            ]);

            ActivityLogger::log(
                'Stock Adjustment',
                "Adjusted {$product->name} from {$oldQuantity} to {$newQuantity}. Reason: {$validated['reason']}",
                'Inventory'
            );
        });

        return back()->with('success', 'Stock adjusted successfully.');
    }
}
