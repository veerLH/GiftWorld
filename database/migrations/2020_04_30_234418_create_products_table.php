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
            $table->bigIncrements('id');
            $table->text('name')->nullable()->comment("Need to save for multiple language");
            $table->string('slug')->unique()->comment('for custom link');
            $table->bigInteger('category_id')->default(0)->comment('for product category');
            $table->integer('rank')->default(0)->comment('for sorting');
            $table->string('main_photo')->comment("product main photo");
            $table->text('feature_photo')->comment("product feature photo");
            $table->double('prices')->default(0)->comment("Product Prices");
            $table->text('description')->comment("Product Description");
            $table->tinyInteger('trash')->default(0);
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
        Schema::dropIfExists('products');
    }
}
