<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Order;
use App\Earning;
use App\Fine;
use App\Http\Controllers\PagesController;
use App\User;
use Carbon\Carbon;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Earning_reports_controller;

class EarningsController extends Controller
{
     public function __construct()
    {
        $this->middleware('auth');

    }
    
    public static function store_earning(Request $request)
    {
    	$current_order = Order::findorfail($request->order_id);

        $earning = new Earning;
        $earning->order_id = $request->order_id;
        $earning->user_id = $current_order->user_id;
        $earning->earning = $current_order->compensation;

        // I need to save the bonus on a separate table
        $earning->bonus = $request->bonus;
        
        if (!$request->user()->ni_admin) {
            return back()->with('error','Wewe si Admin, unajaribu nini?');
        }
        else 
        {
            $earning->admin_id = $request->user()->id;


        if ($current_order->is_late == 1) 
        {
            // $earning->late_fee = 0.1 * $current_order->compensation;

            // I decided to create a new fines table, so we store every fine on that table
            // I create a new fine record using the fines model
            $fine = new Fine;
            $fine->total_fine = 0.1 * $current_order->compensation;
            $fine->order_id = $request->order_id;
            $fine->type_of_fine = 'late';
            $fine->reason ='Order had a late delivery';
            $fine->user_id = $current_order->user_id;
            $fine->status = 1;
            $fine->save();

        }
        else
        {
            $earning->late_fee = 0;
        }

        $earning->total = $earning->earning + $earning->bonus ;
        $earning->approved = 1;


        $earning->save();

        $current_order->approved = 1;

        $current_order->update();


        return back()->with('message', 'Order Approved for payment Successfully');

        }

    	

        
         // return $request->all();
    	
    }
    public function store_fine(Request $request)
    {
            $fine = new Fine;
            $fine->total_fine = $request->total_fine;
            $fine->order_id = $request->order_id;
            $fine->type_of_fine = 'manual';
            $fine->reason = $request->reason;
            $fine->user_id = $request->writer;
            
            $fine->status = 1;
            $fine->save();

            return back()->with('message', 'Fine Applied Successfully');

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
    public function approve(User $user)
    {
        //We check if the total payment is a -ve balance
        $total_earnings = $user->earnings()->where('paid', '0')->sum('total') - $user->fines()->where('status', 1)->sum('total_fine');
        if($total_earnings < 1 )
        {
            return back()->with('error', 'The system cannot approve '.$user->first_name."'s".' earnings because the writer has a -ve balance');
        }


        //We mark all the fines as 0 to show that they have already been effective in the payment
        foreach($user->fines->where('status', 1) as $fine)
        {
            $fine->status = 0;
            $fine->update();
        }

        foreach ($user->earnings as $earning) 
        {
            $earning->paid = 1;
            $earning->paid_date = Carbon::now();
            $earning->update();

        }

            NotificationController::paymentApprovedNotice($user);
            Earning_reports_controller::store_Earning_report($user, $total_earnings);

            return back()->with('approved_order', 'Earning Approved Successfully');
    }

}
