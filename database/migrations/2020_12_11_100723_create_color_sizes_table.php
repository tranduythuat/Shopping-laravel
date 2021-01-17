<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateColorSizesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('color_sizes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_color_id');
            $table->foreign('product_color_id')->references('id')->on('product_colors')->onDelete('cascade');
            $table->unsignedInteger('size_id');
            $table->foreign('size_id')->references('id')->on('sizes')->onDelete('cascade');
            $table->integer('quanity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('color_sizes', function (Blueprint $table) {
            $table->dropForeign('color_sizes_product_color_id_foreign');
            $table->dropColumn('product_color_id');
            $table->dropForeign('color_sizes_size_id_foreign');
            $table->dropColumn('size_id');
        });

        Schema::dropIfExists('color_sizes');
    }
}
