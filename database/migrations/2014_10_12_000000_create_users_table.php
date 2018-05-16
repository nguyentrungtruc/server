<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ec_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->string('email', 255)->unique();
            $table->string('password', 255);
            $table->date('birthday');
            $table->tinyInteger('gender');
            $table->string('address', 255)->nullable();
            $table->float('lat', 10, 6)->nullable();
            $table->float('lng', 10, 6)->nullable();
            $table->string('phone', 15)->unique();
            $table->string('image', 255)->nullable();
            $table->boolean('have_store')->default(0);
            $table->boolean('actived')->default(0);
            $table->tinyInteger('banned')->default(0);
            $table->integer('role_id')->unsigned();
            $table->foreign('role_id')->references('id')->on('ec_roles')->onDelete('cascade');
            $table->string('api_token')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
        DB::update("ALTER TABLE ec_users AUTO_INCREMENT = 100000000;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ec_users');
    }
}
