<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProvisioningRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('provisioning_records', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('service_location_id')->unsigned()->nullable();
            $table->integer('ont_profile_id')->unsigned()->nullable();
            $table->integer('port_id')->unsigned()->nullable();
            $table->integer('ip_address_id')->unsigned()->nullable();
            $table->string('len')->nullable();
            $table->string('circuit_id')->nullable();
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
        Schema::dropIfExists('provisioning_records');
    }
}
