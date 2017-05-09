<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //profile part
        Schema::create('profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('no')->unique();
            $table->string('initial', 5)->unique();
            $table->string('prefix', 10)->nullable();
            $table->string('name', 40);
            $table->string('suffix', 10)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('birthplace', 40);
            $table->date('birthdate');
            $table->integer('user_id')->unsigned();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
        //end profile part

        //education parts
        Schema::create('forms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('form', 30);
            $table->string('description')->nullable();
        });

        Schema::create('countries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 2)->unique();
            $table->string('country', 60)->unique();
        });

        Schema::create('organizations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('organization', 50);
            $table->string('address');
            $table->integer('parent_id')->unsigned()->nullable();
            $table->integer('form_id')->unsigned();
            $table->timestamps();

            $table->foreign('form_id')->references('id')->on('forms')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('educations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('program_id')->unsigned();
            $table->integer('institution_id')->unsigned();
            $table->integer('country_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->timestamps();

            $table->foreign('program_id')->references('id')->on('organizations')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('institution_id')->references('id')->on('organizations')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('country_id')->references('id')->on('countries')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('profiles')->onUpdate('cascade')->onDelete('cascade');
        });
        //end education parts

        //academic or non-academin experience
        Schema::create('experiences', function (Blueprint $table) {
            $table->increments('id');
            $table->string('position', 40);
            $table->integer('organization_id')->unsigned();
            $table->boolean('type'); //if 1 academic if 0 non - academic
            $table->integer('user_id')->unsigned();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->timestamps();

            $table->foreign('organization_id')->references('id')->on('organizations')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('profiles')->onUpdate('cascade')->onDelete('cascade');
        });
        //end academic or non-academin experience

        //Membership in Professional Organizations
        Schema::create('memberships', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->integer('user_id')->unsigned();
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('profiles')->onUpdate('cascade')->onDelete('cascade');
        });
        //end Membership in Professional Organizations

        //awards
        Schema::create('awards', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->integer('user_id')->unsigned();
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('profiles')->onUpdate('cascade')->onDelete('cascade');
        });
        //end certifications

        //activities
        Schema::create('activities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->integer('user_id')->unsigned();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('profiles')->onUpdate('cascade')->onDelete('cascade');
        });
        //end certifications
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profiles');
        Schema::dropIfExists('educations');
        Schema::dropIfExists('experiences');
        Schema::dropIfExists('memberships');
        Schema::dropIfExists('awards');
        Schema::dropIfExists('activities');

        Schema::dropIfExists('organizations');
        Schema::dropIfExists('countries');
        Schema::dropIfExists('forms');

    }
}
