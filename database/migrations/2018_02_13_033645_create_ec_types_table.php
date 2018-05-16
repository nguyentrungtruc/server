<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEcTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ec_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type_name', 50)->unique();
            $table->string('type_slug', 50)->unique();
            $table->string('code', 4)->unique()->nullable();
            $table->string('type_icon', 50)->nullable();
            $table->boolean('type_show')->default(0);
            $table->timestamps();
        });
        DB::update("ALTER TABLE ec_districts AUTO_INCREMENT = 101;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ec_types');
    }
}
