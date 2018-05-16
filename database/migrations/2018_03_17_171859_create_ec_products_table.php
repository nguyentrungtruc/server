<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEcProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ec_products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->string('_name')->nullable();
            $table->decimal('price', 12, 0);
            $table->integer('count')->default(0);
            $table->tinyInteger('have_size')->default(0);
            $table->tinyInteger('have_topping')->default(0);
            $table->text('image')->nullable();
            $table->tinyInteger('priority')->default(0);
            $table->integer('status_id')->unsigned();
            $table->foreign('status_id')->references('id')->on('ec_product_status')->onDelete('cascade');
            $table->integer('catalogue_id')->unsigned();
            $table->foreign('catalogue_id')->references('id')->on('ec_catalogues')->onDelete('cascade');
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
        Schema::dropIfExists('ec_products');
    }
}
