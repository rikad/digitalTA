<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKpCorpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kp_corps', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name', 200);
            $table->text('address')->nullable();
            $table->string('bidang', 128);
            $table->string('phone1', 16)->nullable();
            $table->string('phone2', 16)->nullable();
            $table->string('mail1', 64)->nullable();
            $table->string('mail2', 64)->nullable();
            $table->string('site', 200)->nullable();
            $table->text('description')->nullable();

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
        Schema::dropIfExists('kp_corps');
    }
}
