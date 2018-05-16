<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEcStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ec_stores', function (Blueprint $table) {
            $table->increments('id');
            $table->string('store_name', 255)->unique()->nullable();
            $table->string('store_slug', 255)->unique()->nullable();
            $table->string('store_phone', 13)->nullable();
            $table->string('preparetime', 2)->nullable();
            $table->string('store_address', 255)->nullable();
            $table->float('lat', 10, 6)->nullable();
            $table->float('lng', 10, 6)->nullable();
            $table->text('store_avatar')->nullable();
            $table->boolean('verified')->default(0);
            $table->boolean('store_show')->default(0);
            $table->tinyInteger('priority')->default(0);
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('ec_users')->onDelete('cascade');
            $table->integer('district_id')->unsigned()->nullable();
            $table->foreign('district_id')->references('id')->on('ec_districts')->onDelete('cascade');
            $table->integer('type_id')->unsigned()->nullable();
            $table->foreign('type_id')->references('id')->on('ec_types')->onDelete('cascade');
            $table->integer('status_id')->unsigned()->default(1);
            $table->foreign('status_id')->references('id')->on('ec_store_status')->onDelete('cascade');
            $table->timestamps();
        });
        DB::update("ALTER TABLE ec_stores AUTO_INCREMENT = 100000001;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ec_stores');
    }
}
