<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackageablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packageables', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('package_id');
            $table->integer('packageable_id');
            $table->string('packageable_type');
            $table->timestamps();

            $table->index(['packageable_id','packageable_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('packageables');
    }
}
