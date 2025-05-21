<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commissions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('seller_id')->nullable();
            $table->foreign('seller_id')
                    ->references('id')->on('sellers')
                    ->onDelete('cascade');

            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')
                    ->references('id')->on('categories')
                    ->onDelete('cascade');

            $table->double('commission_rate')->default(0);
            $table->double('promoter_club_fee')->default(0);

            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')
                    ->references('id')->on('users')
                    ->onDelete('SET NULL');

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
        Schema::dropIfExists('commissions');
    }
}
