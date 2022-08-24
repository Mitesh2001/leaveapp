<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaveManagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_manages', function (Blueprint $table) {
            $table->id();
            $table->string('external_id');
            $table->integer('leave_id');
            $table->integer('email_template_id');
            $table->string('clients_list');
            $table->string('alternate_person');
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
        Schema::dropIfExists('leave_manages');
    }
}
