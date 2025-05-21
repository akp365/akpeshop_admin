<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPaymentOptionsAddCarts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_options', function (Blueprint $table) {
            $table->enum('regular_cart', ['on', 'off'])->default('off');
            $table->enum('reward_point_cart', ['on', 'off'])->default('off');
            $table->enum('hot_deal_cart', ['on', 'off'])->default('off');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_options', function (Blueprint $table) {
            $table->dropColumn('regular_cart');
            $table->dropColumn('reward_point_cart');
            $table->dropColumn('hot_deal_cart');
        });
    }
}
