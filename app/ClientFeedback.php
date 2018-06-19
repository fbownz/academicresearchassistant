<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientFeedback extends Model
{	
	// The table we are on
	 protected $table = 'client_feedback';
     // A one to one Relationship with users
    public function writer(){

      return $this->belongsTo(User::class,'writer_ID');
    }
	public function client(){
		return $this->belongsTo(User::class,'client_ID');
	}
	
	
    public function order(){

    	return $this->belongsTo(Order::class,'order_ID');
    }
}
