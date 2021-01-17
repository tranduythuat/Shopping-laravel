<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->name,
            'slug' => $this->faker->slug,
            'description' => $this->faker->paragraph,
            'content' => $this->faker->text, 
            'status' => $this->faker->randomElement(['active', 'inactive']),
            'hot' => $this->faker->randomElement(['no', 'yes']),
            'category_id' => Category::all()->random()->id
        ];
    }
}
