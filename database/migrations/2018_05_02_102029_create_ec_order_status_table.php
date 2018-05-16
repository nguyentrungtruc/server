<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEcOrderStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ec_order_status', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_status_name')->unique();
            $table->string('order_status_description')->nullable();
            $table->tinyInteger('number_order');
            $table->string('color', 25)->nullable();
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
        Schema::dropIfExists('ec_order_status');
    }
}
