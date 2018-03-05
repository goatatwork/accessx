<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOntSoftwareTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ont_software', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ont_id')->unsigned();
            $table->foreign('ont_id')->references('id')->on('onts')->onDelete('cascade');
            $table->string('version');
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('ont_software');
    }
}
