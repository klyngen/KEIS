<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEquipmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipment', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string("model");     // The model ex Canon ix913423
            $table->text("Description");
            $table->integer("brands")->unsigned();
            $table->integer("types")->unsigned();

            // FOREIGN KEY CONSTRAINTS
            $table->foreign("brands")->references('id')->on('brands');
            $table->foreign("types")->references('id')->on('types');  // The type, camera, microphone etc
        });
      }



    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropForeign(['equipment_brands_foreign', 'equipment_types_foreign']);
        Schema::dropIfExists('equipment');
    }
}
