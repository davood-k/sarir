<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKhademyarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('khademyars', function (Blueprint $table) {
            $table->id();
            $table->string('fname')->nullable();
            $table->string('lname');
            $table->string('codemelli',15);
            $table->tinyInteger('gender')->default(0);
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
        Schema::dropIfExists('khademyars');
    }
}
