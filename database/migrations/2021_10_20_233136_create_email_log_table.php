<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('email_logs');
        Schema::create('email_log', function (Blueprint $table) {
            $table->id();
            $table->integer('template_id')->default(0);
            $table->integer('client_id')->default(0);
            $table->string('client_email')->nullable();
            $table->string('email_subject')->nullable();
            $table->string('from_email')->nullable();
            $table->string('from_name')->nullable(); 
            $table->json('email_json')->nullable();
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
        Schema::dropIfExists('email_log');
    }
}
