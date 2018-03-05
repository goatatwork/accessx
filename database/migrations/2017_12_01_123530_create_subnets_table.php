<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubnetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subnets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('dhcp_shared_network_id')->unsigned();
            $table->foreign('dhcp_shared_network_id')->references('id')->on('dhcp_shared_networks')->onDelete('cascade');
            $table->string('comment')->nullable();
            $table->string('network_address');
            $table->string('subnet_mask');
            $table->string('cidr');
            $table->string('start_ip');
            $table->string('end_ip');
            $table->string('routers');
            $table->string('broadcast_address');
            $table->string('dns_servers');
            $table->string('default_lease_time');
            $table->string('max_lease_time');
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
        Schema::dropIfExists('subnets');
    }
}
