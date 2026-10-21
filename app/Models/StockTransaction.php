<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockTransaction extends Model
{
    public const LAST_VIEWED_SESSION_KEY = 'stock_transactions_last_viewed_id';

    protected $fillable = [
        'product_id',
        'type',
        'quantity',
        'remarks',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
