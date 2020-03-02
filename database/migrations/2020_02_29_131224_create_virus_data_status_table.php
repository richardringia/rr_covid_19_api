<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVirusDataStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('virus_data_statuses', function (Blueprint $table) {
            $table->string('id', 100)->primary()->collation('utf8mb4_unicode_ci');
            $table->text('url')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('virus_data', function (Blueprint $table) {
            $table->foreign('status', 'to_status_from_virus_data')->references('id')->on("virus_data_statuses")->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('virus_data_status');
    }
}
