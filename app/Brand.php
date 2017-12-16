<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    public function up() {
        Schema::create("brand", function (Blueprint $table) {
            $table->increments("id");
            $table->string("name");
        });
    }
}
