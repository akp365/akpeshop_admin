<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOpenTicketMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('open_ticket_messages', function (Blueprint $table) {
            $table->id();
            $table->text('ticket_id')->nullable();
            $table->text('user_id')->nullable();
            $table->text('admin_id')->nullable();
            $table->text('vendor_id')->nullable();
            $table->text('message')->nullable();
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
        Schema::dropIfExists('open_ticket_messages');
    }
}
