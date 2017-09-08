<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTranscriptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pc_transcripts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('student_id')->unsigned()->nullable();
            $table->integer('course_id')->unsigned()->nullable();
            $table->integer('year_taken')->unsigned();
            $table->integer('smt_taken')->unsigned();
            $table->string('grade_uni', 2);
            $table->string('grade_ps', 2);
            $table->boolean('is_transcripted');
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
        Schema::dropIfExists('pc_transcripts');
    }
}
