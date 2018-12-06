<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExcludeFromDhcpColumnToIpaddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ip_addresses', function (Blueprint $table) {
            $table->boolean('exclude_from_dhcp')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ip_addresses', function (Blueprint $table) {
            $this->dropColumn('exclude_from_dhcp');
        });
    }
}
