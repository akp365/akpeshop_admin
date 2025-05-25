<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('gift_balances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('currency_id')->constrained()->onDelete('cascade');
            $table->text('description');
            $table->decimal('in', 10, 2)->default(0);
            $table->decimal('out', 10, 2)->default(0);
            $table->enum('status', ['gift_voucher', 'bonus', 'refund']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('gift_balances');
    }
};
