<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockAdjustment extends Model
{
    public const LAST_VIEWED_SESSION_KEY = 'stock_adjustments_last_viewed_id';

    protected $fillable = [
        'product_id',
        'old_quantity',
        'new_quantity',
        'reason',
        'remarks',
    ];

    protected $casts = [
        'old_quantity' => 'integer',
        'new_quantity' => 'integer',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
