<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pc_courses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('curriculum_id')->unsigned();
            $table->integer('no')->unsigned()->nullable();
            $table->string('code', 8);
            $table->string('title', 200)->nullable();
            $table->string('title_en', 200)->nullable();
            $table->integer('semester')->unsigned();
            $table->string('rex', 3);
            $table->integer('sch')->unsigned();
            $table->integer('mbs')->unsigned()->nullable();
            $table->integer('et')->unsigned()->nullable();
            $table->integer('ge')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('curriculum_id')->references('id')->on('pc_curricula')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pc_courses');
    }
}
