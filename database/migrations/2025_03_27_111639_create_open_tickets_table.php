<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOpenTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('open_tickets', function (Blueprint $table) {
            $table->id();
            $table->text('user_id')->nullable();
            $table->text('subject')->nullable();
            $table->text('type')->nullable();
            $table->text('priority')->nullable();
            $table->longText('description')->nullable();
            $table->text('unique_id')->nullable();
            $table->text('status')->default('open');
            $table->boolean('has_new_message')->default(true);
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
        Schema::dropIfExists('open_tickets');
    }
}
