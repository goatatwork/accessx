<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOntsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('onts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('model_number');
            $table->string('manufacturer')->nullable();
            $table->string('slug')->nullable();
            $table->boolean('indoor')->default(false);
            $table->boolean('wifi')->default(false);
            $table->integer('number_of_ethernet_ports')->nullable();
            $table->integer('number_of_pots_lines')->nullable();
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
        Schema::dropIfExists('onts');
    }
}
