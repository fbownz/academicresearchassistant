<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Order;
use App\Earning;
use App\Fine;
use App\Message;
use App\Http\Controllers\PagesController;
use App\User;
use App\Notification;
use Carbon\Carbon;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Earning_reports_controller;
use Illuminate\Support\Facades\Auth;

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
                    

         if (Auth::user()->ni_admin )
        {
            $prof_comp_array = 0;
            $list_messages = Message::where('for_admin', 1)->where('unread', 1)->orderBy('created_at', 'desc')->get();
            $messages_no = $list_messages->count();

            $list_bid_accepted = Notification::where('status',0)->where('type',"admin_order_bidPlaced")->orderBy('created_at', 'desc')->get();
            $list_order_message = Notification::where('status',0)->where('type',"admin_order_message")->orderBy('created_at', 'desc')->get();


            // $list_notifications = $list_order_message->toBase()->merge($list_bid_accepted);
            $notifications_no = 0;
            // I used $list_bid_accepted the same as other users so as not to have a difficult time to display them on the layout

            $order_msg_no = $list_order_message->count();

            // $list_notifications = $list_notifications->sortByDesc('created_at');

            $list_tasks = Notification::where('status',0)->where('type',"admin_order_late")->orderBy('created_at', 'desc')->get();
            $number_tasks =$list_tasks->count() ;
         
        }
        else
        {
             
          $prof_comp_array = NotificationController::profileComplete(Auth::user());

          $list_messages = Auth::user()->messages->where('unread',1)->sortByDesc('created_at');
          $messages_no = $list_messages->count();

          $list_bid_accepted = Auth::user()->notifications()->where('status',0)->where('type','order_bid_accepted')->orderBy('created_at', 'desc')->get();
          $list_order_message = Auth::user()->notifications()->where('status',0)->where('type','order_message')->orderBy('created_at', 'desc')->get();

          // $list_notifications = $list_order_message->toBase()->merge($list_bid_accepted);
           $notifications_no = $list_bid_accepted->count();
           $order_msg_no = $list_order_message->count();
          
          // $list_notifications = $list_notifications->sortByDesc('created_at');



          $list_order_late = Auth::user()->notifications()->where('status',0)->where('type','order_late')->orderBy('created_at', 'desc')->get();
          $list_order_revisions = Auth::user()->notifications()->where('status',0)->where('type','order_revision')->orderBy('created_at', 'desc')->get();

          $list_tasks = $list_order_revisions->toBase()->merge($list_order_late);
          $number_tasks =$list_tasks->count() ;

        }


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
        'messages_no' => $messages_no,
        'list_messages' => $list_messages,
        'order_msg_no' => $order_msg_no,
        'list_order_message' => $list_order_message,
        'notifications_no' => $notifications_no,
        'list_bid_accepted' => $list_bid_accepted,
        'list_tasks' => $list_tasks,
        'number_tasks' => $number_tasks,
        'prof_comp_array' => $prof_comp_array,
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


        //We mark all the fines as 0 to show that they have already been effective in the payments
        foreach($user->fines->where('status', 1) as $fine)
        {
            $fine->status = 0;
            $fine->update();
        }

    // I want to only update unpaid earnings of a particular user
        foreach ($user->earnings->where('paid',0) as $earning) 
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
