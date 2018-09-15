<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEcCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ec_comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('body');
            $table->integer('commentable_id');
            $table->string('commentable_type', 20);
            $table->string('user_agent', 255)->nullable();
            $table->string('ip_address', 100)->nullable();
            $table->bigInteger('parent_id');
            $table->integer('likes')->default(0);
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('ec_users')->onDelete('cascade');
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
        Schema::dropIfExists('ec_comments');
    }
}
