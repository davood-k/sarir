<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComisionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comisions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('khadem_id');
            $table->foreign('khadem_id')->references('id')->on('Khadems');
            $table->integer('documentId')->nullable();
            $table->integer('ShHerasatsr')->nullable();
            $table->string('TnMahalKhsr')->nullable();
            $table->boolean('TdHerasatsr')->default(0);
            $table->integer('ShToliatsr')->nullable();
            $table->boolean('TdToliatsr')->default(0);
            $table->boolean('SiMKhodamsr')->default(0);
            $table->boolean('SiMSarmayehsr')->default(0);
            $table->boolean('SiMAalesr')->default(0);
            $table->boolean('SiToliatsr')->default(0);
            $table->integer('ShHokmsr')->nullable();
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
        Schema::dropIfExists('comisions');
    }
}
