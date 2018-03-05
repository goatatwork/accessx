<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAggregatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aggregators', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('platform_id')->unsigned();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->string('fqdn')->nullable();
            $table->string('management_ip')->nullable();
            $table->string('monitoring_ip')->nullable();
            $table->string('management_mac')->nullable();
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
        Schema::dropIfExists('aggregators');
    }
}
