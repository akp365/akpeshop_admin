<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGiftBalancesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('gift_balances', function (Blueprint $table) {
            $table->id();
            $table->text('description')->nullable();
            $table->decimal('in', 10, 2)->default(0);
            $table->decimal('out', 10, 2)->default(0);
            $table->enum('status', ['redeem', 'bonus', 'refund', 'gift_voucher'])->default('bonus');
            $table->foreignId('user_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreignId('added_cost_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gift_balances');
    }
} 