<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('no_induk', 20)->unique()->nullable();            
            $table->string('username',20)->unique();
            $table->string('nidn', 20)->unique()->nullable();            
            $table->string('prefix', 10)->unique()->nullable();            
            $table->string('name', 200);
            $table->string('suffix', 10)->unique()->nullable();            
            $table->string('email')->unique()->nullable();;
            $table->string('password');

            $table->rememberToken();
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
        Schema::dropIfExists('buku_biru');
        Schema::dropIfExists('group_topic');
        Schema::dropIfExists('groups');
        Schema::dropIfExists('topics');
        Schema::dropIfExists('student_period');
        Schema::dropIfExists('periods');

        Schema::dropIfExists('users');
    }
}
