<?php

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
            $table->string('external_id');
            $table->string('name')->nullable();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('email')->unique();
            $table->string('password', 60);
            $table->bigInteger('primary_number')->nullable();
            $table->string('secondary_number')->nullable();
            $table->string('expertise')->nullable();
            $table->date('date_of_joining')->nullable();
            $table->text('address')->nullable();
            $table->integer('working_hours')->nullable();
            $table->string('profile_pic')->nullable();
            $table->integer('employer_id')->nullable();
            $table->softDeletes();
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
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::drop('users');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
