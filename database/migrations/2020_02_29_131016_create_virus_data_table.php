<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVirusDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('virus_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date');
            $table->bigInteger('count');
            $table->string('type', 100)->nullable();
            $table->string('status', 100)->nullable();
            $table->bigInteger('state')->unsigned();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('state', 'to_state_from_virus_data')->references('id')->on('states')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('virus_data');
    }
}
