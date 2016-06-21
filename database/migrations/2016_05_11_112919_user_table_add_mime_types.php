<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserTableAddMimeTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
          
            $table->string('resume_mime')->nullable();
            $table->string('picha_ya_id_mime')->nullable();
            $table->string('certificate_mime')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('resume_mime')->nullable();
            $table->string('picha_ya_id_mime')->nullable();
            $table->string('certificate_mime')->nullable();
        });
    }
}
