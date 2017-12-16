<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class Type extends Model
{
    public function up() {
        Schema::create("type", function (Blueprint $table) {
            $table->increments("id");
            $table->string("name");
        });
    }

    public function down() {
        Schema::dropIfExists("type");
    }
}
