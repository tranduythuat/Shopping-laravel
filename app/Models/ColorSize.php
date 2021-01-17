<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ColorSize extends Model
{
    use HasFactory;

    protected $table = 'color_sizes';

    protected $fillable = [
        'product_color_id',
        'size_id',
        'quanity'
    ];

    public function productColor()
    {
        return $this->belongsTo(ProductColor::class, 'product_color_id', 'id');
    }

    public function size()
    {
        return $this->belongsTo(Size::class, 'size_id', 'id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'color_size_id', 'id');
    }
}
