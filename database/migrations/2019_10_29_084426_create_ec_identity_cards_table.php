<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEcIdentityCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ec_identity_cards', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('identity_number', 20);
            $table->date('issue_date');
            $table->date('expiry_date');
            $table->string('front_image', 255);
            $table->string('behind_image', 255);
            $table->integer('driver_id')->unsigned();
            $table->foreign('driver_id')->references('id')->on('ec_drivers')->onDelete('cascade');
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
        Schema::dropIfExists('ec_identity_cards');
    }
}
