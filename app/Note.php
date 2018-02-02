<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    // Create a one to one relationship with the orders
    public function order(){

    	return $this->belongsTo(Order::class);
    }
    public function user()
    {
    	return $this->belongsTo(User::class);
    }
    // Create a one to one relationship with the bids
    public function bid(){

    	return $this->belongsTo(Bid::class);
    } 
}
