<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOntProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ont_profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ont_software_id')->unsigned();
            $table->foreign('ont_software_id')->references('id')->on('ont_software')->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->nullable();
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
        Schema::dropIfExists('ont_profiles');
    }
}
