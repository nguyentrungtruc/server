<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEcCityEcRangeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ec_city_ec_range', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('price', 12, 0);
            $table->integer('city_id')->unsigned();
            $table->integer('range_id')->unsigned();
            $table->foreign('city_id')->references('id')->on('ec_cities')->onDelete('cascade');
            $table->foreign('range_id')->references('id')->on('ec_ranges')->onDelete('cascade');
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
        Schema::dropIfExists('ec_city_ec_range');
    }
}
