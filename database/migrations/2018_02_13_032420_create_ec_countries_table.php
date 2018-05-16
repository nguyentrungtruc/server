<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEcCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ec_countries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('country_name', 50)->unique();
            $table->string('lang', 3)->unique();
            $table->string('country_slug', 50);
            $table->float('lat', 10,6)->nullable();
            $table->float('lng', 10,6)->nullable();
            $table->string('dialingcode', 5)->nullable();
            $table->boolean('country_show')->default(0);
            $table->timestamps();
        });
        DB::update("ALTER TABLE ec_countries AUTO_INCREMENT = 1001;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ec_countries');
    }
}
