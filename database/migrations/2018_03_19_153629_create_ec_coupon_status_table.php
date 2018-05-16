<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEcCouponStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ec_coupon_status', function (Blueprint $table) {
            $table->increments('id');
            $table->string('coupon_status_name')->unique();
            $table->string('coupon_status_description')->nullable();
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
        Schema::dropIfExists('ec_coupon_status');
    }
}
