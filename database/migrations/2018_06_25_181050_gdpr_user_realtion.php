<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GdprUserRealtion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        // Makes the users key nullable

        // Recreate the foreign key to make it nullable
        Schema::table('rents', function (Blueprint $table) {
            // Drop the existing foreign key
            $table->dropForeign(['rents_users_foreign']);
            DB::statement('ALTER TABLE `rents` MODIFY `users` INTEGER UNSIGNED NULL');
            $table->foreign('users')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Will not revert the changes
    }
}
