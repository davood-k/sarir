<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKhademsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('khadems', function (Blueprint $table) {
            $table->id();
            $table->string('namesr')->nullable();
            $table->string('familysr');
            $table->string('codemsr', 14);
            $table->string('tdatesr', 10);
            $table->string('moavenat', 10);
            $table->integer('sanvatsr')->default(0);
            $table->integer('enzebatsr')->default(0);
            $table->integer('keifisr')->default(0);
            $table->integer('isarsr')->default(0);
            $table->integer('tahsilsr')->default(0);
            $table->integer('nokhbehsr')->default(0);
            $table->integer('tajmi');
            $table->string('bkhademyarsr');
            $table->string('mobilesr');
            $table->string('dateshsr');
            $table->string('madraksr');
            $table->integer('marhalesr')->default(0);
            $table->text('descriptionsr')->nullable();
            $table->integer('sherkatDarAzsr')->default(0);
            $table->boolean('ShDarComision')->default(0);
            $table->boolean('bayeganisr')->default(0);
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
        Schema::dropIfExists('khadems');
    }
}
