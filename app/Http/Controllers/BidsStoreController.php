<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Order;
use App\Bid;
use App\Http\Controllers\NotificationController;

class BidsStoreController extends Controller
{
	 public function __construct()
    {
        $this->middleware('auth');

    }

    
    // I saw no need for the index() cause it's repetitive
    // public function index()
    // {
    //     return view('pages.bids',[
    //      'site_title' => PagesController::$site_title,
    //     'page_title' => PagesController::$page_title='My Bids',
    //     'page_description' =>PagesController::$page_title='A snapshot of all your orders',
    //     'notifications_no' =>PagesController::$notifications_no,
    //     'list_notifications' =>PagesController::$list_notifications,
    //     'number_tasks' =>PagesController::$number_tasks,
    //     'list_tasks' => PagesController::$list_tasks,
        
        
    //     'user_description' => PagesController::$user_description,
    //     'join_date_text' => PagesController::$join_date_text,
    //     'version_no' => PagesController::$version_no,
    //     ]);
    // }
    
    public static function store(Request $request)
    {
        if(!$request->user()->ni_admin)
        {
            if($request->user()->status !== "1" || $request->user()->verified !==1 )
            {
                return back()->with('error',"You can't bid on the order at this time, you account is not active yet;Kindly  contact the Support for further clarrification.");
            }
            
            $prof_comp_array = NotificationController::profileComplete($request->user());
            if($prof_comp_array['count'] > 0){
                return back()->with('error', 'You cannot bid on any NEW ORDER until you complete your profile and add your bank account');
            }

        }
         $current_order = Order::findorfail($request->order_id);   
        
        if ($current_order->status !=='Available' ) {
            return back()->with('error',"You can't Bid on this order, it has already been assigned");
        }
    
       


    	
        
        $bid = new Bid;
        $bid->order_id = $request->order_id;
        $bid->user_id = $request->user_id;
        $bid->bid_ammount = $request->bid_ammount;
        $bid->bid_msg = $request->bid_msg;
    	$bid->save();

        NotificationController::bidPlaced($request);



    	return back()->with('message', 'Your Bid was placed successfully. 
            Please note that if you are assigned the order you have to deliver it on time');
	
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
