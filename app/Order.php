<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
   	
   	protected $guarded =[
   		'id'];
   	// create a one to many relationship with notes and order_reports and Order_delivery_reports
    public function notes(){

    	return $this->hasMany(Note::class);

    }
    public function order_reports(){
    	return $this->hasMany(order_report::class);
    }

    public function order_delivery_reports(){

      return $this->hasMany(Order_delivery_report::class);
    }
    public function bids(){

        return $this->hasMany(Bid::class);
    }

    // A one to one Relationship with users
    public function user(){

      return $this->belongsTo(User::class);
    }
    public function messages(){

        return $this->hasMany(Message::class);
    }
}
