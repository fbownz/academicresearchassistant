<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Order;
use App\Bid;

class BidsStoreController extends Controller
{
	 public function __construct()
    {
        $this->middleware('auth');

    }
    
    public static function store(Request $request)
    {
    	$current_order = Order::find($request->order_id);
    	
    	$bid = new Bid;
    	$bid->order_id = $request->order_id;
    	$bid->user_id = $request->user_id;
    	$bid->bid_ammount = $request->bid_ammount;
    	$bid->bid_msg = $request->bid_msg;
    	
    	if ($current_order->status !='Available' ) {
    		return back()->with('message',"You can't Bid on this order, it has already been assigned");
    	}
    	
    	$bid->save();


    	return back()->with('message', 'Your Bid was placed successfully. All the best');
	
    }

     public function deleteit(Bid $bid)
    {
        $bid->delete();
        return back()->with('delete_message','The Bid has been Deleted') ;
    }

    public function editit(Request $request, Bid $bid)
    {
        $bid->bid_msg = $request->bid_msg;
        $bid->bid_ammount = $request->bid_ammount;

        $bid->update();

        return back()->with('message','Bid has been edited successfully');
    }
}
