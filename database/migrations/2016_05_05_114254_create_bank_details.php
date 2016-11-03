<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('b_details', function (Blueprint $table) {
            $table->increments('id');
            $table->string('b_name');
            $table->string('b_b_name');
            $table->string('a_name');
            $table->integer('a_number');

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
        Schema::drop('b_details');
    }
}
