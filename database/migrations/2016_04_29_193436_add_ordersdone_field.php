<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrdersdoneField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('earning_reports', function (Blueprint $table) 
        {
            $table->integer('orders_done');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('earning_reports', function (Blueprint $table) {
            //
            $table->integer('orders_done');
        });
    }
}
