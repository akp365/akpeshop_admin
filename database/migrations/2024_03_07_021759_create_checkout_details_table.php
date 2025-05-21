<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckoutDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checkout_details', function (Blueprint $table) {
            $table->id();

            //billing columns
            $table->string("billing_name");
            $table->string("billing_mobile");
            $table->string("billing_email");
            $table->string("billing_address");
            $table->string("billing_zip_code");

            $table->foreignId('billing_country')
                    ->constrained('countries')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');

            $table->foreignId('billing_city')
                    ->constrained('cities')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');

            $table->string("billing_whatsapp")->nullable();
            $table->string("billing_delivery_note")->nullable();
            $table->enum("ship_to_billing_address",['yes','no'])->default('yes');

            //billing columns
            $table->string("shipping_name")->nullable();
            $table->string("shipping_mobile")->nullable();
            $table->string("shipping_email")->nullable();
            $table->string("shipping_address")->nullable();
            $table->string("shipping_zip_code")->nullable();

            $table->foreignId('shipping_country')->nullable()
                    ->constrained('countries')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');

            $table->foreignId('shipping_city')->nullable()
                    ->constrained('cities')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');

            $table->string("shipping_whatsapp")->nullable();
            $table->string("shipping_delivery_note")->nullable();

            $table->foreignId('checkout_id')
                    ->constrained('checkouts')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            
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
        Schema::dropIfExists('checkout_details');
    }
}
