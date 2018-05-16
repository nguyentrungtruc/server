<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEcCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ec_cities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('city_name', 50)->unique();
            $table->string('city_slug', 50)->unique();
            $table->string('zipcode', 10)->nullable();
            $table->float('lat', 10, 6)->nullable();
            $table->float('lng', 10, 6)->nullable();
            $table->boolean('city_show')->default(0);
            $table->integer('country_id')->unsigned();
            $table->foreign('country_id')->references('id')->on('ec_countries')->onDelete('cascade');
            $table->timestamps();
        });
        DB::update("ALTER TABLE ec_cities AUTO_INCREMENT = 10001;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ec_cities');
    }
}
