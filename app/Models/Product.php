<?php

namespace App\Models;

use App\Models\Concerns\BelongsToUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use BelongsToUser;

    protected $fillable = [
        'name',
        'category',
        'price',
        'stock',
        'min_stock',
        'unit',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'stock' => 'integer',
            'min_stock' => 'integer',
            'user_id' => 'integer',
        ];
    }

    public function isLowStock(): bool
    {
        return $this->stock <= $this->min_stock;
    }

    public function stockEntries(): HasMany
    {
        return $this->hasMany(StockEntry::class);
    }

    public function stockExits(): HasMany
    {
        return $this->hasMany(StockExit::class);
    }
}
