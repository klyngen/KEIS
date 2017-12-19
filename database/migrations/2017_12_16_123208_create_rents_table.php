<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      // TODO - is it important to keep user information? LEGAL ISSUE
        Schema::create('rents', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('users')->unsigned();
            $table->integer('instances')->unsigned();

            // FOREIGN KEY CONSTRAINTS
            $table->foreign('users')->references('id')->on('users');
            $table->foreign('instances')->references('id')->on('instances');
            $table->timestamps();
            $table->dateTime("stop")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropForeign(['rents_instances_foreign', 'rents_users_foreign']);

        Schema::dropIfExists('rents');
    }
}
https://github.com/xcwen/ac-phphttps://github.com/xcwen/ac-php