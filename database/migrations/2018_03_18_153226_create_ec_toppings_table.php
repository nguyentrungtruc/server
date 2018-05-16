<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEcToppingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ec_toppings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->string('_name', 255);
            $table->decimal('price', 12, 0);
            $table->integer('store_id')->unsigned();
            $table->foreign('store_id')->references('id')->on('ec_stores')->onDelete('cascade');
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
        Schema::dropIfExists('ec_toppings');
    }
}
