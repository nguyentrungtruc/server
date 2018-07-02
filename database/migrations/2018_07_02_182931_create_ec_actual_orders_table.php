<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEcActualOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ec_actual_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('delivery_price', 12, 0);
            $table->decimal('subtotal_amount', 12, 0);
            $table->decimal('amount', 12, 0);
            $table->string('coupon')->nullable();
            $table->string('secret')->nullable();
            $table->integer('discount')->default(0);
            $table->decimal('discount_total', 12, 0)->default(0);
            $table->integer('order_id')->unsigned();
            $table->foreign('order_id')->references('id')->on('ec_regular_orders')->onDelete('cascade');
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
        Schema::dropIfExists('ec_actual_orders');
    }
}
