<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEcCataloguesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ec_catalogues', function (Blueprint $table) {
            $table->increments('id');
            $table->string('catalogue', 50);
            $table->string('_catalogue', 50)->nullable();
            $table->tinyInteger('priority')->default(0);
            $table->string('slug', 50);
            $table->boolean('catalogue_show')->default(1);
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
        Schema::dropIfExists('ec_catalogues');
    }
}
