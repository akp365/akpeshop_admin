<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingFeeSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_fee_settings', function (Blueprint $table) {
            $table->id();
            $table->decimal('shipping_fee_0_to_1000')->default(0.00);
            $table->decimal('shipping_fee_1001_to_3000')->default(0.00);
            $table->decimal('shipping_fee_3001_to_5000')->default(0.00);
            $table->decimal('shipping_fee_5001_to_10000')->default(0.00);
            $table->decimal('shipping_fee_10001_to_15000')->default(0.00);
            $table->decimal('shipping_fee_above_15000')->default(0.00);

            $table->unsignedBigInteger('seller_id');
            $table->foreign('seller_id')->references('id')->on('sellers');
            
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
        Schema::dropIfExists('shipping_fee_settings');
    }
}
