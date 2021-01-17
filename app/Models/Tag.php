<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'status'
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_tag', 'product_id', 'tag_id');
                    // ->withPivot('status')
                    // ->withTimestamps();
    }

    public function posts()
    {
        return $this->hasMany(Post::class)
                    ->withTimestamps();
    }
}
