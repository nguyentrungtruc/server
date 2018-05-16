<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEcRegularOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ec_regular_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',100);
            $table->string('address');
            $table->float('lat', 10, 6);
            $table->float('lng', 10, 6);
            $table->float('distance', 10, 2); 
            $table->string('phone',12);
            $table->date('date');
            $table->string('time');
            $table->decimal('delivery_price', 12, 0);
            $table->decimal('subtotal_amount', 12, 0);
            $table->decimal('amount', 12, 0);
            $table->string('coupon')->nullable();
            $table->string('secret')->nullable();
            $table->integer('discount')->default(0);
            $table->decimal('discount_total', 12, 0)->default(0);
            $table->string('memo',500)->nullable();
            $table->text('note')->nullable();
            $table->text('paymentinfo')->nullable();
            $table->string('security',40)->nullable();
            $table->integer('employee_id')->unsigned()->nullable();
            $table->foreign('employee_id')->references('id')->on('ec_users')->onDelete('cascade');
            $table->integer('shipper_id')->unsigned()->nullable();
            $table->foreign('shipper_id')->references('id')->on('ec_users')->onDelete('cascade');
            $table->integer('payment_id')->unsigned();
            $table->foreign('payment_id')->references('id')->on('ec_payment_methods')->onDelete('cascade');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('ec_users')->onDelete('cascade');
            $table->integer('store_id')->unsigned();
            $table->foreign('store_id')->references('id')->on('ec_stores')->onDelete('cascade');
            $table->integer('status_id')->unsigned()->default(1);
            $table->foreign('status_id')->references('id')->on('ec_order_status')->onDelete('cascade');
            $table->timestamps();
        });
        DB::update("ALTER TABLE ec_regular_orders AUTO_INCREMENT = 100000001;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ec_regular_orders');
    }
}
