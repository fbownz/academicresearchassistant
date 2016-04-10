<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
     // A one to one Relationship with users
    public function user(){

      return $this->belongsTo(User::class);
    }
    public function order(){

    	return $this->belongsTo(Order::class);
    }
}
