<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductImages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');;

            $table->string("image_1")->nullable();
            $table->string("image_2")->nullable();
            $table->string("image_3")->nullable();
            $table->string("image_4")->nullable();
            $table->string("image_5")->nullable();
            $table->string("image_6")->nullable();
            $table->string("image_7")->nullable();
            $table->string("image_8")->nullable();
            $table->string("image_9")->nullable();
            $table->string("image_10")->nullable();


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
        Schema::dropIfExists('product_images');
    }
}
