<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEcDriversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ec_drivers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('fist_name', 128);
            $table->string('last_name', 128);
            $table->string('email', 255)->unique();
            $table->string('password', 255);
            $table->date('birthday');
            $table->tinyInteger('gender');
            $table->string('address', 255)->nullable();
            $table->string('phone', 15)->unique();
            $table->boolean('working');
            $table->string('image', 255)->nullable();
            $table->tinyInteger('banned')->default(0);
            $table->integer('ban_id')->unsigned();
            $table->foreign('ban_id')->references('id')->on('ec_ban')->onDelete('cascade');
            $table->integer('role_id')->unsigned();
            $table->foreign('role_id')->references('id')->on('ec_roles')->onDelete('cascade');
            $table->string('api_token')->nullable();
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
        Schema::dropIfExists('ec_drivers');
    }
}
