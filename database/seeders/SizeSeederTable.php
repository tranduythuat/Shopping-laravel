<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class SizeSeederTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sizes = ['31', '32', '33', '34', '35', '36', '37', '38', '39', '40'];

        for($i= 0; $i < count($sizes); $i++){
            DB::table('sizes')->insert([
                'name' => $sizes[$i],
                'slug' => $sizes[$i],
                'status' => Arr::random(['active', 'inactive'])
            ]);
        }
    }
}
