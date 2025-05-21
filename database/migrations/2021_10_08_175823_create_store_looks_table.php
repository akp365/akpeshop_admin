<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreLooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_looks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')
                    ->constrained('sellers')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            $table->string("logo")->nullable();
            $table->string("banner")->nullable();
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
        Schema::dropIfExists('store_looks');
    }
}
