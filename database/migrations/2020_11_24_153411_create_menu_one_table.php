<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuOneTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('menu_one')) {
            Schema::create('menu_one', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->integer('order_num');
                $table->enum('status', ["active","inactive"])->default("active");
                $table->string('url')->nullable();
                $table->unsignedBigInteger('page_id')->nullable();
                $table->foreign('page_id')
                        ->references('page_id')->on('pages')
                        ->onDelete('SET NULL');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu_one');
    }
}
