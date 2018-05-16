<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEcStoreStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ec_store_status', function (Blueprint $table) {
            $table->increments('id');
            $table->string('store_status_name', 25)->unique();
            $table->string('store_status_description')->nullable();
            $table->string('color',25)->nullable();
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
        Schema::dropIfExists('ec_store_status');
    }
}
