<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCityServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ec_city_services', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('delivery_actived')->default(0);
            $table->integer('min_amount')->default(0);
            $table->integer('max_amount')->default(0);
            $table->float('min_range')->default(0);
            $table->float('max_range')->default(0);
            $table->time('start_time')->default('08:00:00');
            $table->time('end_time')->default('21:00:00');
            $table->integer('city_id')->unsigned();
            $table->foreign('city_id')->references('id')->on('ec_cities')->onDelete('cascade');
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
        Schema::dropIfExists('ec_city_services');
    }
}
