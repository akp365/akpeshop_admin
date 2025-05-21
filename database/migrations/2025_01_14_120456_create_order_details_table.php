<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->text('product_id')->nullable();
            $table->text('items_details')->nullable();
            $table->text('qty')->nullable();
            $table->text('price')->nullable();
            $table->text('tax')->nullable();
            $table->text('tax_type')->nullable();
            $table->text('wieght')->nullable();
            $table->text('color')->nullable();
            $table->text('size')->nullable();
            $table->text('final_price')->nullable();
            $table->text('product_price')->nullable();
            $table->text('commison')->nullable();
            $table->text('promoter_fee')->nullable();
            $table->text('vat_on_fee')->nullable();
            $table->text('checkout_details')->nullable();
            $table->text('seller_id')->nullable();
            $table->text('product_price')->nullable();
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
        Schema::dropIfExists('order_details');
    }
}
