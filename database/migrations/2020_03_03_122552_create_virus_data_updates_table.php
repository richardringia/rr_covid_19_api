<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVirusDataUpdatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('virus_data_updates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('virus_data_type');
            $table->dateTimeTz('date');
            $table->bigInteger('new');
            $table->bigInteger('changes');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('virus_data_type', 'to_virus_data_type_from_vdu')->references('id')->on('virus_data_types')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('virus_data_updates');
    }
}
