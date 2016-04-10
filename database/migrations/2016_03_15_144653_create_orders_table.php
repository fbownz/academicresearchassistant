<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint, $table){
            $table->increments('id');
            $table->string('type_of_product');
            $table->string('subject');
            $table->integer('word_length');
            $table->string('spacing');
            $table->string('academic_level');
            $table->datetime('deadline');
            $table->float('total');
            $table->string('style');
            $table->string('attachment');
            $table->integer('no_of_sources');
            $table->string('title');
            $table->longtext('instructions');
            $table->longtext('essential_sources');
            $table->longtext('suggested_sources');
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
        //
    }
}
