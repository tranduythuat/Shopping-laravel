<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'name',
        'phone',
        'address',
        'status',
        'amount',
        'payment',
        'message'
    ];


    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function productColorSizes()
    {
        return $this->belongsToMany(ProductColorSize::class)
                    ->as('order_detail')
                    ->withTimestamps();
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'transaction_id', 'id');
    }
}
