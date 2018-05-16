<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEcAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ec_addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('line_1', 255);
            $table->string('line_2', 255);
            $table->string('city', 50);
            $table->string('country', 50);
            $table->float('lat', 10, 6);
            $table->float('lng', 10, 6);
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('ec_users')->onDelete('cascade');
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
        Schema::dropIfExists('ec_addresses');
    }
}
