<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductColorImageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_color_image', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_color_id');
            $table->foreign('product_color_id')->references('id')->on('product_colors')->onDelete('cascade');
            $table->unsignedInteger('image_id');
            $table->foreign('image_id')->references('id')->on('images')->onDelete('cascade');
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
        Schema::table('product_color_image', function (Blueprint $table) {
            $table->dropForeign('product_color_image_product_color_id_foreign');
            $table->dropColumn('product_color_id');
            $table->dropForeign('product_color_image_image_id_foreign');
            $table->dropColumn('image_id');
        });

        Schema::dropIfExists('product_color_image');
    }
}
