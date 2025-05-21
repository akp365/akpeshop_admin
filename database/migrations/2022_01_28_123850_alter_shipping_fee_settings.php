<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterShippingFeeSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shipping_fee_settings', function (Blueprint $table) {
            $table->renameColumn('shipping_fee_0_to_1000', 'shipping_fee_1_to_1000');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shipping_fee_settings', function (Blueprint $table) {
            $table->renameColumn('shipping_fee_1_to_1000', 'shipping_fee_0_to_1000');
        });
    }
}
