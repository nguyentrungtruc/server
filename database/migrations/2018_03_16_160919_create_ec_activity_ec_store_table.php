<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEcActivityEcStoreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ec_activity_ec_store', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('store_id')->unsigned();
            $table->integer('activity_id')->unsigned();
            $table->text('times')->nullable();
            $table->foreign('store_id')->references('id')->on('ec_stores')->onDelete('cascade');
            $table->foreign('activity_id')->references('id')->on('ec_activities')->onDelete('cascade');
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
        Schema::dropIfExists('ec_activity_ec_store');
    }
}
