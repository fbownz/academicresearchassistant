<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    // Create a one to one relationship with the orders
    public function order(){

    	return $this->belongsTo(Order::class);
    }
}
