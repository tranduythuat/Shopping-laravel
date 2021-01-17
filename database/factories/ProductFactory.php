<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->title,
            'slug' => $this->faker->slug,
            'price' => $this->faker->numberBetween(1000, 1000000),
            'sale' => $this->faker->randomElement([10, 20, 30, 50]),
            'status' => $this->faker->randomElement(['active', 'inactive']),
            'hot' => $this->faker->randomElement(['no', 'yes']),
            'description' => $this->faker->paragraph,
            'content' => $this->faker->text,
            'image_path' => $this->faker->imageUrl(),
            'category_id' => Category::all()->random()->id,
            'supplier_id' => Supplier::all()->random()->id,
            'publicId' => '1/fsljlfa/falkjfslk'
        ];
    }
}
