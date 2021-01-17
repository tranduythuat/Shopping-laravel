<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'price',
        'sale',
        'status',
        'hot',
        'quanity',
        'view',
        'description',
        'content',
        'category_id',
        'supplier_id',
        'image_path',
        'publicId'
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'product_tag', 'product_id', 'tag_id')
                    ->withTimestamps();
    }

    public function productColors()
    {
        return $this->hasMany(ProductColor::class, 'product_id', 'id');
    }

    public function colors()
    {
        return $this->belongsToMany(Color::class, 'product_colors', 'product_id', 'color_id');
    }

    public function colorSizes()
    {
        return $this->hasManyThrough(
            ProductColor::class,
            ColorSize::class,
            'color_size_id',
            'product_id',
            'id',
            'id'
        );
    }
}
