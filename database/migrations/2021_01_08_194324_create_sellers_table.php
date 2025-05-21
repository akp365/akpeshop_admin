<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sellers', function (Blueprint $table) {
            $table->id();

            //NEEDED FOR PRE-APPROVAL
            $table->string('name');
            $table->enum('gender',['male', 'female','other'])->default('male');
            $table->integer('age');
            $table->enum('account_type',['individual', 'business'])->default('individual');
            $table->string('shop_name');
            $table->string('company_name')->nullable();
            $table->string('product_categories');

            $table->unsignedBigInteger('country_id');
            $table->foreign('country_id')->references('id')->on('countries');

            $table->unsignedBigInteger('city_id');
            $table->foreign('city_id')->references('id')->on('cities');

            $table->text("shop_address");
            $table->text("company_address")->nullable();
            $table->string("phone");
            $table->string("email");
            $table->enum('account_status',['pre_approval_pending', 'pre_declined', 'pre_approved', 'final_approval_pending', 'final_declined','active', 'inactive'])->default('pre_approval_pending');
            $table->string('password');

            //NEEDED FOR FINAL-APPROVAL
            $table->string("photo_url")->nullable();
            $table->string("nid_url")->nullable();
            $table->string("tin_certificate_url")->nullable();
            $table->string("trade_license_url")->nullable();
            $table->string("gst_url")->nullable();
            $table->string("bank_check_url")->nullable();
            $table->enum('market_place_agreement',['yes', 'no'])->default('yes');

            //NEEDED FOR ACTIVE VENDORS
            $table->string("seller_code")->nullable();
            $table->timestamp("member_since")->nullable();

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
        Schema::dropIfExists('sellers');
    }
}
