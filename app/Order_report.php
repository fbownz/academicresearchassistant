<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order_report extends Model
{
    // Create a one to one relationship with the orders
    public function order(){

    	return $this->belongsTo(Order::class);
    }
}
