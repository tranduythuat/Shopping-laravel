<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'price',
        'status',
        'quanity',
        'transaction_id',
        'customer_id',
        'color_size_id'
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'id');
    }

    public function colorSize()
    {
        return $this->belongsTo(ColorSize::class, 'color_size_id', 'id');
    }
}
