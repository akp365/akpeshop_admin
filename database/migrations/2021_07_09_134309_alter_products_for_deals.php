<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterProductsForDeals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->integer('reward_point')->nullable();
            $table->string('hot_deal')->nullable();
            $table->integer('deal_time')->nullable();
            $table->timestamp('deal_start', 0)->nullable();
            $table->string('deal_start_1', 0)->nullable();
            $table->timestamp('deal_end', 0)->nullable();
            $table->string('deal_end_1', 0)->nullable();
            $table->timestamp('approved_on', 0)->nullable();
            $table->timestamp('declined_on', 0)->nullable();
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
            $table->dropColumn([ 'reward_point', 'hot_deal', 'deal_time', 'deal_start', 'deal_start_1', 'deal_end', 'deal_end_1', 'approved_on', 'declined_on' ]);
        });
    }
}
