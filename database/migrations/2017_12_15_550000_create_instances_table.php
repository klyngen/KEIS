<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instances', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('condition');
            $table->date('purchasetime');
            $table->integer("equipment")->unsigned();
            $table->foreign('equipment')->references('id')->on('equipment');
            $table->char("RFID", 20)->unique();
            $table->boolean("rented")->default(0);
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
        Schema::dropForeign(['equipment:_instances_foreign']);
        Schema::dropIfExists('instances');
    }
}
