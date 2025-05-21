<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFooterSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('footer_settings')) {
            Schema::create('footer_settings', function (Blueprint $table) {
                $table->id();
                $table->string('key');
                $table->string('label');
                $table->string('value');

                //PAGE ID FOREIGN KEY
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
        Schema::dropIfExists('footer_settings');
    }
}
