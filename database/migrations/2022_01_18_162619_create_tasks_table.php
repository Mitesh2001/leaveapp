<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('external_id');
            $table->string('title');
            $table->unsignedInteger('leave_id');
            $table->integer('behalf_of');
            $table->integer('done_by')->unsigned();
            $table->foreign('done_by')->references('id')->on('users');
            $table->unsignedInteger('client_id');
            $table->foreign('client_id')->references('id')->on('clients');
            $table->text('description')->nullable();
            $table->json('time_taken');                
            $table->unsignedInteger('project_id')->nullable();                    
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
        Schema::dropIfExists('tasks');
    }
}
