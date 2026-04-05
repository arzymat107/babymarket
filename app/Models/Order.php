<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'customer_name',
        'customer_phone',
        'shipping_address',
        'shipping_cost',
        'total_amount',
        'paid',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'shipping_cost' => 'decimal:2',
            'total_amount'  => 'decimal:2',
            'paid'          => 'boolean',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}