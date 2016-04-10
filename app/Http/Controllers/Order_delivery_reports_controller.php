<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Order;
use App\Order_delivery_report;

class Order_delivery_reports_controller extends Controller
{
     public function __construct()
    {
        $this->middleware('auth');

    }
    
    public static function store_report(Request $request)
    {
    	$current_order = Order::find($request->order_id);
    	

    	$Order_delivery_report = new Order_delivery_report;
    	$file_url = $request->file('file')->move(storage_path(), $request->file('file')->getClientOriginalName());
    	

    	$Order_delivery_report->order_id = $request->order_id;
    	$Order_delivery_report->user_id = $current_order->user_id;
    	$Order_delivery_report->is_complete = $request->is_complete;
    	$Order_delivery_report->file_location = $file_url;
    	$current_order->is_complete = $request->is_complete;

    	$Order_delivery_report->save();

    	if ($request->is_complete) {
    		$current_order->status = "Delivered";

		}
		else{
			$current_order->status = "Draft";
		}
    	
    	$current_order->update();

    	return back()->with('message', 'Paper uploaded Successfully');
    	
    }
}
