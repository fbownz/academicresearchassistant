<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bonus extends Model
{
	// I specify the table the model will work with
	protected $table = 'bonuses';
    //
     public function user(){

    	return $this->belongsTo(User::class);
    }
    public function order()
    {
    	return $this->belongsTo(Order::class);
    }
}
