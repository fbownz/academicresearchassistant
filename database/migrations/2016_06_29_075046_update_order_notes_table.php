<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateOrderNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notes', function (Blueprint $table) {
            //
            $table->string('original_attachment_name')->nullable();
            $table->string('attachment_name')->nullable();
            $table->string('attachment_mime')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notes', function (Blueprint $table) {
            //
            $table->string('original_attachment_name')->nullable();
            $table->string('attachment_name')->nullable();
            $table->string('attachment_mime')->nullable();
        });
    }
}
