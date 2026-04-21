<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    protected $fillable = [
        'customer_name',
        'phone',
        'total',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
