<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('studentlogs', function (Blueprint $table) {
            $table->id();
            $table->char('student_id')->foreign()->references('id')->on('students');
            $table->string('action_type');
            $table->string('ticketId');
            $table->string('reopenId')->nullable();
            $table->string('userId')->nullable();
            $table->string('userEmail');
            // $table->longText('action_description')->nullable();
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
        Schema::dropIfExists('studentlogs');
    }
};