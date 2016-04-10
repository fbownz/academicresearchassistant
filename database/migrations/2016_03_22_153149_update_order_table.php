<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function($table)
        {
            // We will use to store the order ID we get from the add order form and later from woocommerce
            //$table->string('order_id')->nullable();
            // We will use this to store the status whether the order is available or not
            //$table->string('status')->nullable();
            // We will use this to set the total amount that the writer is to receive
            //$table->integer('compensation')->nullable();
            $table->integer('user_id')->nullable()->unsigned()->index();
            
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
