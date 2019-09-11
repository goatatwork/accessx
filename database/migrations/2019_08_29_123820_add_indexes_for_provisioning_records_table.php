<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexesForProvisioningRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('provisioning_records', function (Blueprint $table) {
            $table->index('service_location_id');
            $table->index('port_id');
            $table->index('ont_profile_id');
            $table->index('ip_address_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('provisioning_records', function (Blueprint $table) {
            //
        });
    }
}
