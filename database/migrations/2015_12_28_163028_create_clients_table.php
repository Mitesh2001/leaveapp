<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->increments('id');
            $table->string('external_id');
            $table->string('name')->nullable();
            $table->string('service_type')->nullable();
            $table->string('project_name')->nullable();
            $table->string('primary_number');
            $table->string('secondary_number')->nullable();
            $table->string('primary_email');
            $table->string('secondary_email')->nullable();
            $table->string('primary_contact_person');
            $table->string('secondary_contact_person')->nullable();
            $table->integer('country_id');
            $table->integer('state_id');
            $table->string('city')->nullable();
            $table->text('address')->nullable();
            $table->integer('zipcode')->nullable();
            $table->string('company_name')->nullable();
            $table->string('company_type')->nullable();
            $table->text('notes')->nullable();
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
        Schema::drop('clients');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
