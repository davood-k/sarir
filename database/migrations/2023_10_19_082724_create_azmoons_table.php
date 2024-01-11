<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAzmoonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('azmoons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('khadem_id');
            $table->foreign('khadem_id')->references('id')->on('Khadems');
            $table->integer('nomrehAzmoonsr')->nullable();
            $table->text('dalil')->nullable();
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
        Schema::dropIfExists('azmoons');
    }
}
