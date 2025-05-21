<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInfoChangeRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('info_change_requests', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('seller_id');
            $table->foreign('seller_id')->references('id')->on('sellers');

            //NEEDED FOR PRE-APPROVAL
            $table->string('name')->nullable();
            $table->enum('gender',['male', 'female','other'])->nullable();
            $table->integer('age')->nullable();
            $table->enum('account_type',['individual', 'business'])->nullable();
            $table->string('shop_name')->nullable();
            $table->string('company_name')->nullable();
            $table->string('product_categories')->nullable();

            $table->unsignedBigInteger('country_id')->nullable();
            $table->foreign('country_id')->references('id')->on('countries');

            $table->unsignedBigInteger('city_id')->nullable();
            $table->foreign('city_id')->references('id')->on('cities');

            $table->text("shop_address")->nullable();
            $table->text("company_address")->nullable();
            $table->string("phone")->nullable();
            $table->string("email")->nullable();

            //NEEDED FOR FINAL-APPROVAL
            $table->string("photo_url")->nullable();
            $table->string("nid_url")->nullable();
            $table->string("tin_certificate_url")->nullable();
            $table->string("trade_license_url")->nullable();
            $table->string("gst_url")->nullable();
            $table->string("bank_check_url")->nullable();

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
        Schema::dropIfExists('info_change_requests');
    }
}
