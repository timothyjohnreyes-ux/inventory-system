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
            ->when($dateFilters['start_date'] ?? null, function ($query, $startDate) {
                $query->whereDate('created_at', '>=', $startDate);
            })
            ->when($dateFilters['end_date'] ?? null, function ($query, $endDate) {
                $query->whereDate('created_at', '<=', $endDate);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('stock-adjustments.index', compact(
            'adjustments',
            'search',
            'reason',
            'reasons',
            'startDate',
            'endDate'
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
