<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTugasAkhir extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //periods part
        Schema::create('periods', function (Blueprint $table) {
            $table->increments('id');

            $table->string('year', 4);
            $table->string('semester', 1);

            $table->timestamps();
        });
        //end topik part

        //periods part
        Schema::create('student_period', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('student_id')->unsigned()->nullable();
            $table->integer('period_id')->unsigned()->nullable();

            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('period_id')->references('id')->on('periods')->onUpdate('cascade')->onDelete('cascade');
        });
        //end topik part

        //topik part
        Schema::create('topics', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->integer('dosen1_id')->unsigned();
            $table->integer('dosen2_id')->unsigned()->nullable();
            $table->integer('bobot')->unsigned();
            $table->integer('waktu')->unsigned();
            $table->integer('dana')->unsigned();
            $table->integer('period_id')->unsigned();

            $table->boolean('is_taken')->unsigned()->default(0);
            $table->timestamps();

            $table->foreign('dosen1_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('dosen2_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('period_id')->references('id')->on('periods')->onUpdate('cascade')->onDelete('cascade');
        });
        //end topik part


        //group part
        Schema::create('groups', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('student1_id')->unsigned();
            $table->integer('student2_id')->unsigned()->nullable();
            $table->integer('period_id')->unsigned();
            $table->boolean('status')->unsigned()->default(0); //0: mengajukan, 1: disetujui, 2: ditolak
            $table->timestamps();

            $table->foreign('student1_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('student2_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('period_id')->references('id')->on('periods')->onUpdate('cascade')->onDelete('cascade');
        });
        //end group part

        //group_topic part
        Schema::create('group_topic', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('group_id')->unsigned();
            $table->integer('topic_id')->unsigned();
            $table->boolean('status')->unsigned()->default(0); //0: mengajukan, 1: disetujui, 2: ditolak
            $table->text('note')->nullable();
            $table->timestamps();

            $table->foreign('group_id')->references('id')->on('groups')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('topic_id')->references('id')->on('topics')->onUpdate('cascade')->onDelete('cascade');
        });
        //end group part

        //group_topic part
        Schema::create('buku_biru', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('group_id')->unsigned();
            $table->date('tanggal_bimbingan');
            $table->text('kegiatan');
            $table->text('note');
            $table->string('attachment', 200);
            $table->timestamps();

            $table->foreign('group_id')->references('id')->on('groups')->onUpdate('cascade')->onDelete('cascade');
        });
        //end group part
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('buku_biru');
        Schema::dropIfExists('group_topic');
        Schema::dropIfExists('groups');
        Schema::dropIfExists('topics');
        Schema::dropIfExists('student_period');
        Schema::dropIfExists('periods');
    }
}