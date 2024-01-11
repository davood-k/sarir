<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDefinationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('definations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('khademyar_id');
            $table->foreign('khademyar_id')->references('id')->on('khademyars');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('sh_letter');
            $table->string('date_letter');
            $table->string('moarefi', 50);
            $table->string('moavenat', 50);
            $table->string('molahezat', 200)->nullable();
            $table->text('tozih', 1000)->nullable();
            $table->tinyInteger('deleted');
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
        Schema::dropIfExists('definations');
    }
}
