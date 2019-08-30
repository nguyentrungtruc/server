<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEcRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ec_ratings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('body')->nullable();
            $table->tinyInteger('value')->default(4);
            $table->integer('ratingtable_id');
            $table->string('ratingtable_type', 20);
            $table->string('user_agent', 255)->nullable();
            $table->string('ip_address', 100)->nullable();
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('ec_users')->onDelete('cascade');
            $table->integer('rating_type_id')->unsigned();
            $table->foreign('rating_type_id')->references('id')->on('ec_rating_types')->onDelete('cascade');
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
        Schema::dropIfExists('ec_ratings');
    }
}
