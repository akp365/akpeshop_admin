<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellerPayablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seller_payables', function (Blueprint $table) {
            $table->id();
            $table->text('tax')->default('not_selected');
            $table->text('shipping_fee')->default('not_selected');
            $table->text('cod_charge')->default('not_selected');
            $table->text('coupon_discount')->default('not_selected');
            $table->text('commision')->default('not_selected');
            $table->text('promoter_fee')->default('not_selected');
            $table->text('vat_on_fee')->default('not_selected');
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
        Schema::dropIfExists('seller_payables');
    }
}
