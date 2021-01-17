<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductColorImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'image_name',
        'image_path',
        'publicId',
        'product_color_id'
    ];
}
