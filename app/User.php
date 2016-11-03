<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
// Create a one to many relationship with the orders
    public function orders(){

        return $this->hasMany(Order::class);
    }

    public function earnings(){

        return $this->hasMany(Earning::class);
    }
    public function bids(){

        return $this->hasMany(Bid::class);
    }
    public function messages(){

        return $this->hasMany(Message::class);
    }
    public function order_delivery_reports()
    {
        return $this->hasMany(Order_delivery_report::class);
    }
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
    public function earning_reports()
    {
        return $this->hasMany(Earning_report::class);
    }
    public function b_details()
    {
        return $this->hasMany(B_detail::class);
    }
    public function fines()
    {
        return $this->hasMany(Fine::class);
    }
    public function bonuses()
    {
        return $this->hasMany(Bonus::class);
    }

}
