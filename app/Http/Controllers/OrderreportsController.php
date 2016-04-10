<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Order;
use App\Order_report;
class OrderreportsController extends Controller
{
     public function __construct()
    {
        $this->middleware('auth');

    }
    
    public static function store_report($request)
    {
    	//$current_order = Order::find($request->order_id);
        

    	$Order_report = new Order_report;
    	$Order_report->order_id = $request->order_id;
    	$Order_report->user_id = $request->user_id;
    	$Order_report->order_status = $request->status;
        $Order_report->writer_assigned = $request->writer;

    	$Order_report->save();
    	
    }
}
