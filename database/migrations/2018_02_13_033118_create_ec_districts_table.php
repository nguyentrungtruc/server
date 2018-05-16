<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEcDistrictsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ec_districts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('district_name', 50)->unique();
            $table->string('district_slug', 50)->unique();
            $table->float('lat', 10, 6)->nullable();
            $table->float('lng', 10, 6)->nullable();
            $table->boolean('district_show')->default(0);
            $table->integer('city_id')->unsigned();
            $table->foreign('city_id')->references('id')->on('ec_cities')->onDelete('cascade');
            $table->timestamps();
        });
        DB::update("ALTER TABLE ec_districts AUTO_INCREMENT = 100001;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ec_districts');
    }
}
