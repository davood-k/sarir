<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInformationOfficesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('information_offices', function (Blueprint $table) {
            $table->id();
            $table->string('offices');
            $table->string('personsRelation');
            $table->string('numbers');
            $table->integer('mobiles')->nullable();
            $table->text('address');
            $table->integer('post');
            $table->integer('timeServices');
            $table->text('description');
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
        Schema::dropIfExists('information_offices');
    }
}
