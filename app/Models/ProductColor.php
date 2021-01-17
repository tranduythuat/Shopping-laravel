<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductColor extends Model
{
    use HasFactory;

    protected $table = 'product_colors';

    protected $fillable = [
        'product_id',
        'color_id',
    ];

    public function colorSizes()
    {
        return $this->hasMany(ColorSize::class, 'product_color_id', 'id');
    }

    public function sizes()
    {
        return $this->belongsToMany(Size::class, 'color_sizes', 'product_color_id', 'size_id')
                    ->withPivot('quanity')
                    ->withTimestamps();
    }

    public function images()
    {
        return $this->belongsToMany(Image::class, 'product_color_image', 'product_color_id', 'image_id', 'id');
    }

    public function color()
    {
        return $this->belongsTo(Color::class, 'color_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
