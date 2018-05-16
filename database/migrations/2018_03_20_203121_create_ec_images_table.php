<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEcImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ec_images', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('quantity');
            $table->text('image_url');
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
        Schema::dropIfExists('ec_images');
    }
}
