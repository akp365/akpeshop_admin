<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('seller_id');
            $table->foreign('seller_id')->references('id')->on('sellers');

            $table->string('product_code');
            $table->enum('status', array('active', 'pending', 'declined', 'inactive'))->default('pending');
            $table->string('name');
            $table->enum("product_type", array("Regular", "Reward Point Offer", "Hot Deal", "eProducts", "Get Service"))->default("Regular");
            $table->enum("product_declaration", array("Dangerous Good","Battery","Flamable", "Liquid", "None"))->default("None");

            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories');

            $table->unsignedBigInteger('sub_category_id');
            $table->foreign('sub_category_id')->references('id')->on('categories');

            $table->enum("warranty_type", array("No Warranty", "Brand Warranty", "Seller Warranty"))->default("No Warranty");
            $table->integer("warranty_period")->nullable();//in gm
            $table->integer("weight")->nullable();//in gm
            $table->integer("length")->nullable();//in cm
            $table->integer("width")->nullable();//in cm
            $table->integer("height")->nullable();//in cm
            $table->string("brand_name")->nullable();
            $table->string("brand_image")->nullable();
            $table->enum("wholesale_availability", array("Available","Not Available"));
            $table->integer("wholesale_minimum_quantity")->nullable();
            $table->double("wholesale_price_per_unit",10,2)->nullable();
            $table->text("shipping_method")->nullable();
            $table->enum("world_wide_shipping", array("yes", "no"))->default("no");

            //-- SHIPPING FEES
            $table->decimal('shipping_fee_0_to_1000')->default(0.00);
            $table->decimal('shipping_fee_1001_to_3000')->default(0.00);
            $table->decimal('shipping_fee_3001_to_5000')->default(0.00);
            $table->decimal('shipping_fee_5001_to_10000')->default(0.00);
            $table->decimal('shipping_fee_10001_to_15000')->default(0.00);
            $table->decimal('shipping_fee_above_15000')->default(0.00);

            //-- PRICES
            $table->decimal('retail_price')->default(0.00);
            $table->decimal('discount_pct')->default(0.00);
            $table->decimal('selling_price')->default(0.00);
            
            $table->unsignedBigInteger('shipping_currency')->nullable();
            $table->foreign('shipping_currency')->references('id')->on('currencies');

            $table->text("minimum_shipping_time")->nullable();
            $table->text("maximum_shipping_time")->nullable();
            $table->enum("tax_option", array("Included", "Excluded", "Not Applicable"))->default("Included");
            $table->string("tax_title")->nullable();
            $table->decimal("tax_pct")->nullable();

            $table->string("video_url")->nullable();
            $table->enum('video_mode', array('url', 'upload', 'none'))->default('none');
            $table->text("size_details")->nullable();
            $table->longText("product_description");
            $table->longText("buy_and_return_policy");



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
        Schema::dropIfExists('products');
    }
}
