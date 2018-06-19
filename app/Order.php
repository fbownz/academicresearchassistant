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
      return $this->hasMany(Order_report::class);
    }
    public function client_feedbacks(){
      return $this->hasMany(ClientFeedback::class);
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
    public function earning()
    {
      return $this->hasOne(Earning::class);
    }
    public function notifications(){
      return $this->hasMany(Notification::class);
    }
    public function fines()
    {
      return $this->hasMany(Fine::class);
    }
    public function bonuses()
    {
        return $this->hasMany(Bonus::class);
    }
    
    public function files()
    {
      return $this->hasMany(Files::class);
    }

    public function transactions()
    {
      return $this->hasMany(Transaction::class);
    }
   public function clientfeedback()
	{
		return $this->hasMany(ClientFeedback::class);
	}
}
