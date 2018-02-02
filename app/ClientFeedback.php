<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientFeedback extends Model
{	
	// The table we are on
	 protected $table = 'client_feedback';
     // A one to one Relationship with users
    public function user(){

      return $this->belongsTo(User::class);
    }
    public function order(){

    	return $this->belongsTo(Order::class);
    }
}
