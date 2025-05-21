<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryChangeRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_change_requests', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('seller_id')->nullable();
            $table->foreign('seller_id')
                    ->references('id')->on('sellers')
                    ->onDelete('cascade');

            $table->unsignedBigInteger('old_cat')->nullable();
            $table->foreign('old_cat')
                    ->references('id')->on('categories')
                    ->onDelete('cascade');

            $table->unsignedBigInteger('new_cat')->nullable();
            $table->foreign('new_cat')
                    ->references('id')->on('categories')
                    ->onDelete('cascade');

            $table->enum('status',['pending', 'approved', 'declined'])->default('pending');
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
        Schema::dropIfExists('category_change_requests');
    }
}
