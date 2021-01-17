<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=1;$i<=9; $i++){
            DB::table('product_tag')->insert([
                'product_id' => Product::all()->random()->id,
                'tag_id' => Tag::all()->random()->id,
            ]);
        }
    }
}
