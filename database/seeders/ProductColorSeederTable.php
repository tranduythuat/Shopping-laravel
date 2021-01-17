<?php

namespace Database\Seeders;

use App\Models\Color;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductColorSeederTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=1; $i <=9 ; $i ++){
            DB::table('product_colors')->insert([
                'product_id' => Product::all()->random()->id,
                'color_id' => Color::all()->random()->id,
            ]);
        }
    }
}
