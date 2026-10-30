<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'sku',
        'quantity',
        'price',
        'description',
        'is_active',
        'reorder_level',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'reorder_level' => 'integer',
            'price' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function stockTransactions(): HasMany
    {
        return $this->hasMany(StockTransaction::class);
    }

    public function isLowStock(): bool
    {
        return $this->quantity <= $this->reorder_level;
    }

    public function stockAdjustments(): HasMany
    {
        return $this->hasMany(StockAdjustment::class);
    }
}
