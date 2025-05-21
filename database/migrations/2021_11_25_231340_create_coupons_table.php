<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string("coupon_code")->nullable();
            $table->enum("coupon_type", array("PCT", "Fixed-Amt"));
            $table->decimal("coupon_amount", $precision = 8, $scale = 2)->nullable();
            $table->date("expiration_date")->nullable();

            $table->boolean("coupon_quantity_unlimited")->default(1);//BY DEFAULT UNLIMITED
            $table->bigInteger("coupon_quantity")->nullable();//IF UNLIMITED TRUE QUANTITY BE NULL

            $table->bigInteger("each_user_limit")->nullable();
            $table->decimal("minimum_spend", $precision = 8, $scale = 2)->nullable();
            $table->decimal("maximum_spend", $precision = 8, $scale = 2)->nullable();

            $table->boolean("applicable_for_all_products")->default(1);//ALL PRODUCTS FOR SELECTED TYPE

            $table->enum("status",['active', 'expired'])->default('active')->nullable();

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
        Schema::dropIfExists('coupons');
    }
}
