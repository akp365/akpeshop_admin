<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChangeLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('change_logs', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('seller_id')->nullable();
            $table->foreign('seller_id')
                    ->references('id')->on('sellers')
                    ->onDelete('cascade');

            $table->text("description")->nullable(); //e.g: name change from 'A' to 'B' OR add new category 'CAT-1' OR category change from 'CAT-A' to 'CAT-B'
            $table->string("attribute_name");
            $table->string("old_value");
            $table->string("new_value");
            $table->enum("change_status",['approved','declined']);

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
        Schema::dropIfExists('change_logs');
    }
}
