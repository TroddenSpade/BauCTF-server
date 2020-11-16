<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->string('format');
            $table->boolean('open');
            $table->boolean('record');
            $table->char('code',100);
            $table->dateTime('start');
            $table->dateTime('end');
            $table->smallInteger('max_score');
            $table->smallInteger('min_score');
            $table->tinyInteger('no_c');
            $table->json('scoreboard')->nullable();
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
        Schema::dropIfExists('events');
    }
}
