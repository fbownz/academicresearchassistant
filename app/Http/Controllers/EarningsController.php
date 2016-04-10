<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Order;
use App\Earning;
use App\Http\Controllers\PagesController;

class EarningsController extends Controller
{
     public function __construct()
    {
        $this->middleware('auth');

    }
    
    public static function store_earning(Request $request)
    {
    	$current_order = Order::find($request->order_id);
    	
    	$earning = new Earning;
    	$earning->earnings_no = $earning->earnings_no +1 ;
    	$earning->order_id = $request->order_id;
    	$earning->user_id = $current_order->user_id;
    	$earning->earning = $current_order->compensation;
    	
    	if ($request->user()->ni_admin !=1 ) {
    		return back()->with('message','Wewe si Admin, unajaribu nini?');
    	}
    	$earning->admin_id = $request->user()->id;


    	if ($current_order->is_late == 1) {
    		$earning->late_fee = 0.1 * $current_order->compensation;

    	}
    	else{
    		$earning->late_fee = 0;
    	}

    	$earning->total = $earning->earning - $earning->late_fee;
    	$earning->approved = 1;


    	$earning->save();

    	$current_order->approved = 1;

    	$current_order->update();

    	return back()->with('message', 'Order Approved for payment Successfully');

    	
    }
    public function show()
    {
                    


    	return View('pages.earnings',[ 
 		'site_title' => PagesController::$site_title,
        'page_title' => PagesController::$page_title='Earnings',
        'page_description' =>PagesController::$page_title='A snapshot of all the recent earnings',
       	'notifications_no' =>PagesController::$notifications_no,
        'list_notifications' =>PagesController::$list_notifications,
        'number_tasks' =>PagesController::$number_tasks,
        'list_tasks' => PagesController::$list_tasks,
        
        
        'user_description' => PagesController::$user_description,
        'join_date_text' => PagesController::$join_date_text,
        'version_no' => PagesController::$version_no,
        ]);
    }
}
