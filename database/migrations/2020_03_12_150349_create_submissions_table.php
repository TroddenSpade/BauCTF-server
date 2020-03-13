<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('e_id');
            $table->foreignId('c_id');
            $table->foreignId('u_id');
            $table->foreign('e_id')->references('id')->on('events');
            $table->foreign('c_id')->references('id')->on('challenges');
            $table->foreign('u_id')->references('id')->on('users');
            $table->char('flag',32);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('submissions');
    }
}
