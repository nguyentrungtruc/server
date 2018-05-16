<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEcCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ec_coupons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 255);
            $table->string('coupon', 15);
            $table->text('notes')->nullable();
            $table->tinyInteger('discount_percent');
            $table->integer('max_coupons');
            $table->integer('coupon_used')->default(0);
            $table->datetime('started_at');
            $table->datetime('ended_at');
            $table->integer('expires_at');
            $table->string('token', 255);
            $table->boolean('actived')->default(0);
            $table->integer('status_id')->unsigned();
            $table->foreign('status_id')->references('id')->on('ec_coupon_status')->onDelete('cascade');
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
        Schema::dropIfExists('ec_coupons');
    }
}
