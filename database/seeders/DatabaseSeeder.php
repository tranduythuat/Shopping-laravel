<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\Category;
use App\Models\Color;
use App\Models\Image;
use App\Models\Post;
use App\Models\ProductColorSize;
use App\Models\ProductImage;
use App\Models\Slider;
use App\Models\Tag;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->count(1)->create();
        // Color::factory()->count(10)->create();
        // $this->call(SizeSeederTable::class);
        // Supplier::factory()->count(10)->create();
        // Category::factory()->count(10)->create();
        // Product::factory()->count(10)->create();
        // Tag::factory()->count(10)->create();
        // $this->call(ProductTagSeeder::class);
        // Slider::factory()->count(10)->create();
        // Post::factory()->count(10)->create();
        // $this->call(ProductColorSeederTable::class);
        // $this->call(ColorSizeSeederTable::class);
    }
}
