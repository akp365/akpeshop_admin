<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckoutItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checkout_items', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('checkout_id');
            $table->foreign('checkout_id')->references('id')->on('checkouts');

            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products');

            $table->unsignedBigInteger('variant_id');
            $table->foreign('variant_id')->references('id')->on('product_stocks');

            $table->integer("quantity")->nullable();
            $table->float("weight")->nullable();
            $table->float("final_price")->nullable();
            $table->enum("type", array("Regular", "Reward Point Offer", "Hot Deal"))->default("Regular");

            $table->unsignedBigInteger('seller_id');
            $table->foreign('seller_id')->references('id')->on('sellers');
            $table->text('order_status')->default('placed');

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
        Schema::dropIfExists('checkout_items');
    }
}
