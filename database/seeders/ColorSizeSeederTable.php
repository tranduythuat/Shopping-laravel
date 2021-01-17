<?php

namespace Database\Seeders;

use App\Models\Size;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ColorSizeSeederTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=1; $i <=10 ; $i ++){
            DB::table('color_sizes')->insert([
                'product_color_id' => 3,
                'size_id' => Size::all()->random()->id,
                'quanity' => '10'
            ]);
        }
    }
}
