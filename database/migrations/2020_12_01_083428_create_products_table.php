<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->decimal('price', 15, 4)->default(0.0000);
            $table->integer('sale');
            $table->enum('status', ['active', 'inactive']);
            $table->enum('hot', ['no', 'yes']);
            $table->integer('quanity')->default(0);
            $table->integer('view')->default(0);
            $table->text('description');
            $table->longText('content');
            $table->unsignedInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->unsignedInteger('supplier_id');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
            $table->string('image_path');
            $table->string('publicId');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign('products_category_id_foreign');
            $table->dropColumn('category_id');
            $table->dropForeign('products_supplier_id_foreign');
            $table->dropColumn('supplier_id');
            $table->dropSoftDeletes();
        });
        Schema::dropIfExists('products');
    }
}
