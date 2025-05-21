<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductQAndATables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //-- CUSTOMERS TABLE
        if (!Schema::hasTable('customers')) {
            Schema::create('customers', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');

                $table->unsignedBigInteger('country_id')->nullable();
                $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');

                $table->string("phone"); 
                $table->enum('gender',['male', 'female','other'])->default('male');
                $table->integer('age');
                $table->enum('receive_offers',['yes', 'no'])->default('no');
                $table->rememberToken();
                $table->timestamps();
            });
        }


        //-- PRODUCT Q-&-A TABLE
        if (!Schema::hasTable('product_questions')) {
            Schema::create('product_questions', function (Blueprint $table) {
                $table->id();

                $table->unsignedBigInteger('product_id')->nullable();
                $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

                $table->text('question');

                $table->unsignedBigInteger('customer_id')->nullable();
                $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
                $table->integer('helpfull')->default(0);
                $table->integer('not_helpfull')->default(0);
                $table->integer('reports')->default(0);
                $table->enum("status", ['active', 'inactive'])->default('active');


                $table->timestamps();
            });
        }


        //-- PRODUCT Q-&-A ANSWERS TABLE
        if (!Schema::hasTable('product_answers')) {
            Schema::create('product_answers', function (Blueprint $table) {
                $table->id();

                $table->unsignedBigInteger('question_id');
                $table->foreign('question_id')->references('id')->on('product_questions')->onDelete('cascade');

                $table->text('answer');

                $table->enum('answered_by', ['customer', 'vendor']);

                $table->unsignedBigInteger('customer_id')->nullable();
                $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');

                $table->timestamps();
            });
        }


        //-- PRODUCT Q-&-A COMMENTS TABLE
        if (!Schema::hasTable('q_and_a_comments')) {
            Schema::create('q_and_a_comments', function (Blueprint $table) {
                $table->id();

                $table->unsignedBigInteger('question_id');
                $table->foreign('question_id')->references('id')->on('product_questions')->onDelete('cascade');

                $table->text('comment');

                $table->unsignedBigInteger('customer_id')->nullable();
                $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');

                $table->timestamps();
            });
        }


        //-- PRODUCT REVIEWS
        if (!Schema::hasTable('product_reviews')) {
            Schema::create('product_reviews', function (Blueprint $table) {
                $table->id();

                $table->unsignedBigInteger('product_id')->nullable();
                $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

                $table->unsignedBigInteger('customer_id')->nullable();
                $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');

                $table->integer('rating');
                $table->string('comments')->nullable();
                $table->integer('helpfull')->default(0);
                $table->integer('not_helpfull')->default(0);

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
        Schema::dropIfExists('product_answers');
        Schema::dropIfExists('q_and_a_comments');
        Schema::dropIfExists('product_questions');
        Schema::dropIfExists('product_reviews');
        Schema::dropIfExists('customers');
    }
}
