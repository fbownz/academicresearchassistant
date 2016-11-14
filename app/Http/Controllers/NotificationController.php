<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use App\Order;
use App\Notification;
use App\Note;
use Mail;
use App\Http\Controllers\PagesController;
use App\Bid;
use App\Sms_notice;
use App\Failed_sms_notice;
use Illuminate\Support\Facades\Auth;
use App\Message;

//We add the AfricasTalkingGateway Class in order to send SMS notifications
use App\AfricasTalkingGateway; 
use Carbon\Carbon;


// We use this class to create different notifications that appear on the site and on the emails

class NotificationController extends Controller
{
    
    public static function bidAcceptednotice(User $user, Order $order)
    {

        $notification = new Notification;
        $notification->user_id = $user->id;
        $notification->order_id = $order->id;
        $notification->type = 'order_bid_accepted';
        $notification->message ='Accepted';

        // $notification->save();
        $deadline = Carbon::parse($order->deadline)->format('F j, Y H:i A');

        Mail::queue('emails.order_assigned',['user'=>$user, 'order'=>$order, 'deadline' =>$deadline],function ($m) use ($user, $order, $deadline)
        {
            $m->from('noreply@academicresearchassistants.com','ARA');

            $m->to($user->email, $user->first_name)->subject('Academicresearch: Congrats! Your bid has been accepted on order '.$order->order_no);
        });

        
        //We send an sms notification to the User for the assigned order;
        $txt = 'Congratulations '.$user->first_name.' your bid for Order '.$order->order_no.' has been accepted. Deliver the paper by '.$deadline."\n".' Academicresearchassistants.com';
        $send_sms =self::sendSMSnotice($user,$txt);

    }
    public static function orderRevisionnotice(User $user, Order $order)
    {

        $notification = new Notification;
        $notification->user_id = $user->id;
        $notification->order_id = $order->id;
        $notification->type = 'order_revision';
        $notification->message ='Revisions';

        $notification->save();

        Mail::queue('emails.order_revision',['user'=>$user,  'order'=>$order],function ($m) use ($user, $order)
        {
            $m->from('notifications@academicresearchassistants.com','ARA');

            $m->to($user->email, $user->first_name)->subject('Academicresearch: Revision required on Order '.$order->order_no);
        });

        //We send an sms notification to the User for the assigned order;
        $txt = 'Order '.$order->order_no.' Needs urgent revision. Make efforts to make the changes and upload the file'."\n".' academicresearchassistants.com';
        $send_sms =self::sendSMSnotice($user,$txt);

    }
    public static function orderMessageNotice(User $user, Order $order)
    {
        $notification = new Notification;
        $notification->user_id = $user->id;
        $notification->order_id = $order->id;
        $notification->type = 'order_message';
        $notification->message ='New Message';

        $notification->save();

        Mail::queue('emails.order_message',['user'=>$user, 'order'=>$order,'notification'=>$notification],function ($m) use ($user, $order)
        {
            $m->from('noreply@academicresearchassistants.com','ARA');

            $m->to($user->email, $user->first_name)->subject('You have a New message on Order '.$order->order_no);
        });

        //We send an sms notification to the User for order Message;
        $txt = 'You have a new Message on Order '.$order->order_no.' Kindly check the order to respond'."\n".' Academicresearchassistants.com';
        $send_sms =self::sendSMSnotice($user,$txt);
    }
    public static function adminOrderMessageNotice(Order $order)
    {

        $notification = new Notification;
        $notification->user_id = $order->user->id;
        $notification->order_id = $order->id;
        $notification->type = 'admin_order_message';
        $notification->message ='New Message';

        $notification->save();
        $admins = User::where('ni_admin', 1)->get();
        foreach ($admins as $admin) {
            # code...
            $admin_email = $admin->email;
            $admin_name = $admin->first_name;

            Mail::queue('emails.admin_order_message',['order'=>$order, 'notification'=>$notification],function ($m) use ($admin_email, $admin_name, $order)
            {
                $m->from('noreply@academicresearchassistants.com','ARA');

                $m->to($admin_email, $admin_name)->subject('Academicresearch: You have a New message on order '.$order->order_no);
            });

        //We send an sms notification to the User for order Message;
            // For the purpose of the text I save the admin as the user
        $user = $admin;
        $txt = 'There is a new Message on Order '.$order->order_no.' Kindly check the order to respond'."\n".' Academicresearchassistants.com';
        $send_sms =self::sendSMSnotice($user,$txt);
        }   

    }

    public static function orderDeliveryNotice(User $user, Order $order)
    {

        Mail::queue('emails.order_delivery',['user'=>$user, 'order'=>$order], function($m)use ($user, $order)
        {
            $m->from('notifications@academicresearchassistants.com', 'ARA');

            $m->to($user->email, $user->first_name)->subject('Academicresearch: We have received your '.$order->status. ' paper for '.$order->order_no);

        });
    }
    public static function adminOrderDeliveryNotice(User $user, Order $order)
    {

        $notification = new Notification;
        $notification->user_id = $order->user->id;
        $notification->order_id = $order->id;
        $notification->type = 'admin_order_delivery';
        $notification->message ='Order Delivered';

        $notification->save();
        $admins = User::where('ni_admin', 1)->get();
        foreach ($admins as $admin) {
            # code...
            $admin_email = $admin->email;
            $admin_name = $admin->first_name;

            Mail::queue('emails.admin_order_delivery',['user'=>$user, 'order'=>$order],function ($m) use ($admin_email, $admin_name,$order)
        {
            $m->from('notifications@academicresearchassistants.com','ARA');

            $m->to($admin_email, $admin_name)->subject('Order #'.$order->order_no.' has been delivered');
        });
        }

        
    }

    public static function lateOrdersNotice(User $user, Order $order)
    {
        $notification = new Notification;
        $notification->user_id = $user->id;
        $notification->order_id = $order->id;
        $notification->type = 'order_late';
        $notification->message ='Late Bid Placed';

        $notification->save();

        Mail::queue('emails.late_order',['user'=>$user, 'order'=>$order], function($m)use ($user, $order)
        {
            $m->from('noreply@academicresearchassistants.com', 'ARA');

            $m->to($user->email, $user->first_name)->subject('Academicresearch: Late Order Notice! for Order '.$order->order_no);

        });


        //We save another record on the database for the Admin notice
        $notification = new Notification;
        $notification->user_id = $order->user->id;
        $notification->order_id = $order->id;
        $notification->type = 'admin_order_late';
        $notification->message ='Late Order';

        $notification->save();
        // I send an email to admins on the same
        $admins = User::where('ni_admin', 1)->get();
        foreach ($admins as $admin) {
            # code...
            $admin_email = $admin->email;
            $admin_name = $admin->first_name;

            Mail::queue('emails.admin_late_order',['user'=>$user, 'order'=>$order],function ($m) use ($admin_email, $admin_name,$order)
            {
                $m->from('notifications@academicresearchassistants.com','ARA');

                $m->to($admin_email, $admin_name)->subject('Academicresearch: Late Order Notice! for Order '.$order->order_no);
            });
        }

        //We send an sms notification to the User for the late order;
        $txt = 'Order '.$order->order_no.' is late. A late fee fine has been applied. Kindly upload it as soon as possible to avoid futher penalties.  Academicresearchasistants.com';
        $send_sms =self::sendSMSnotice($user,$txt);

    }

    // Send a notification to the writer when the delivery time of a particular order has been changed
    public static function delivery_time_changed(User $user, Order $order)
    {
        $notification = new Notification;
        $notification->user_id = $user->id;
        $notification->order_id = $order->id;
        $notification->type = 'Deadline update';
        $notification->message ='Deadline has been updated';

        $notification->save();

        Mail::queue('emails.time_updated_order',['user'=>$user, 'order'=>$order], function($m)use ($user, $order)
        {
            $m->from('noreply@academicresearchassistants.com', 'ARA');

            $m->to($user->email, $user->first_name)->subject('Deadline updated on Order '.$order->order_no);

        });


        // //We save another record on the database for the Admin notice
        // $notification = new Notification;
        // $notification->user_id = $order->user->id;
        // $notification->order_id = $order->id;
        // $notification->type = 'Delivery time updated';
        // $notification->message ='Delivery time has been updated';

        // $notification->save();
        // // I send an email to admins on the same
        // $admins = User::where('ni_admin', 1)->get();
        // foreach ($admins as $admin) {
        //     # code...
        //     $admin_email = $admin->email;
        //     $admin_name = $admin->first_name;

        //     Mail::queue('emails.admin_late_order',['user'=>$user, 'order'=>$order],function ($m) use ($admin_email, $admin_name,$order)
        //     {
        //         $m->from('notifications@academicresearchassistants.com','ARA');

        //         $m->to($admin_email, $admin_name)->subject('Academicresearch: Late Order Notice! for Order '.$order->order_no);
        //     });
        // }

        //We send an sms notification to the User for the time update;
        $txt = 'Greetings '.$user->first_name.' Order '.$order->order_no.' deadline has been updated to '.$order->deadline.' Kindly have a look at it and take not of the the chagnes. Academicresearchasistants.com';
        $send_sms =self::sendSMSnotice($user,$txt);
    }

    public static function clientdeliveryTimeNotice(Order $order)
    {
       
        // I want to create a notification only if the order has been assigned if it hasn't been assigned I go straight to send the sms and email;

        $notification = new Notification;
        if ($order->user_id) {
            
            $notification->user_id = $order->user->id;
        }
        else{
            $notification->user_id = 13;
            //I put my test user id for the sake of a notification count
        }
        $notification->order_id = $order->id;
        $notification->type = 'order_past_client_deadline';
        $notification->message = "Order is past client's deadline";
        $notification->status = 1;
        $notification->save();

            // We send SMS notifications to the admins
            $text ='The Client deadline for Order #'.$order->order_no.' has elapsed. Kindly check if it was uploaded by the writer and delivered to the client successfully.';

            $admins = User::where('ni_admin',1)->get();
            if ($admins->count()) {
                foreach ($admins as $admin) {
                    
                   self::sendSMSnotice($admin, $text);

                   $admin_email = $admin->email;
                   $admin_name = $admin->first_name;

                   Mail::queue('emails.client_deadline_notice',['admin'=>$admin, 'order'=>$order],function ($m) use ($admin_email, $admin_name,$order)
                    {
                        $m->from('noreply@academicresearchassistants.com','ARA');

                        $m->to($admin_email, $admin_name)->subject('Order #'.$order->order_no.' client deadline has elapsed');
                    });
                }
            }


    }

    // We save the record on the database for the admin side
    public static function bidPlaced(Request $request)
    {
        $notification = new Notification;
        $notification->user_id = $request->user_id;
        $notification->order_id = $request->order_id;
        $notification->type = 'admin_order_bidPlaced';
        $notification->message ='New Bid Placed';

        $notification->save();
    }


    public static function paymentApprovedNotice(User $user)
    {

        $notification = new Notification;
        $notification->user_id = $user->id;
        $notification->order_id = null;
        $notification->type = 'Payment approved';
        $notification->message ='Payment approved';

        $notification->save();

        Mail::queue('emails.payment_approved',['user'=>$user], function($m)use ($user)
        {
            $m->from('notifications@academicresearchassistants.com', 'ARA');

            $m->to($user->email, $user->first_name)->subject('Academicresearch: Congrats payments approved ');

        });
    }
    // public static function update_notifications()
    // {
    //     # code...
    //     foreach(Notification::where('status',0)->where('message','Payment approved')->get() as $notification){
    //         $notification->status = 1;
    //         $notification->update();
    //     }
    // }
    public function view(Request $request, Order $order, Notification $notification)
    {   
        if(!$request->user()->ni_admin){
            if($order->status !== "Available" && $order->user_id !== $request->user()->id){
                Return redirect('orders')->with('error','You are not allowed to view that Order. Kindly contact Admin for further Assistance');
            }
        }
        $users_to_be_assigned = User::has('bids')->where('verified', 1)->where('status','1')->where('ni_admin', null)->get();


        $bids_submitted = Bid::where('order_id','=',$order->id)->orderBy('created_at','asc')->get();

        $notification->status = 1;
        $notification->update();

         if (Auth::user()->ni_admin )
        {
            $prof_comp_array = 0;
            $list_messages = Message::where('for_admin', 1)->where('unread', 1)->orderBy('created_at', 'desc')->get();
            $messages_no = $list_messages->count();

            $list_bid_accepted = Notification::where('status',0)->where('type',"admin_order_bidPlaced")->orderBy('created_at', 'desc')->get();
            $list_order_message = Notification::where('status',0)->where('type',"admin_order_message")->orderBy('created_at', 'desc')->get();


            // $list_notifications = $list_order_message->toBase()->merge($list_bid_accepted);
            $notifications_no = $list_bid_accepted->count();
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

          $list_bid_accepted = Auth::user()->notifications()->where('status',0)->where('type','order_bid_accepted')->orderBy('created_at', 'asc')->get();
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

         return view('pages.order', [ 
        'site_title' => PagesController::$site_title,
        'page_title' => $order->order_no,
        'page_description' =>PagesController::$page_description='Order details',
        
        
        'user_description' => PagesController::$user_description,
        'join_date_text' => PagesController::$join_date_text,
        'version_no' => PagesController::$version_no,
        'order' => $order,
        'writers' => $users_to_be_assigned,
        'bids' => $bids_submitted,
        'messages_no' => $messages_no,
        'list_messages' => $list_messages,
        'order_msg_no' => $order_msg_no,
        'list_order_message' => $list_order_message,
        'notifications_no' => $notifications_no,
        'list_bid_accepted' => $list_bid_accepted,
        'list_tasks' => $list_tasks,
        'number_tasks' => $number_tasks,
        'prof_comp_array' => $prof_comp_array, ]);
    }

    public static function profileComplete(User $user)
    {
        
        $prof_comp_array = [];
        $prof_comp_array['b_detail'] = 1;
        $prof_comp_array['simu1'] = 1;
        $prof_comp_array['shule_level'] = 1;
        $prof_comp_array['copy_ya_id'] = 1;
        $prof_comp_array['cv'] = 1;
        $prof_comp_array['count'] = 0;
                
         if($user->b_details->count() <1)
          {
            $prof_comp_array['b_detail'] = 0;
            $prof_comp_array['count'] = $prof_comp_array['count'] +1;
          }    
          if(!$user->phone1)
          {
            $prof_comp_array['simu1'] = 0;
            $prof_comp_array['count'] = $prof_comp_array['count'] +1;
          }    
          if(!$user->academic_level)
          {
            $prof_comp_array['shule_level'] = 0;
            $prof_comp_array['count'] = $prof_comp_array['count'] +1;
          }    
          if(!$user->picha_ya_id)
          {
            $prof_comp_array['copy_ya_id'] = 0;
            $prof_comp_array['count'] = $prof_comp_array['count'] +1;
          }    
          if(!$user->certificate)
          {
            $prof_comp_array['cert'] = 0;
            $prof_comp_array['count'] = $prof_comp_array['count'] +1;
          }    
          if(!$user->resume )
          {
            $prof_comp_array['cv'] = 0;
            $prof_comp_array['count'] = $prof_comp_array['count'] +1;
          }

          return $prof_comp_array; 
    }
    public static function sendSMSnotice(User $user, $txt)
    {   
        //implement the AfricaisTalkingAPI here
        if($user->phone1){

            $recipients = $user->phone1;
        }
        elseif($user->phone2){
            $recipients = $user->phone2;
        }
        else{
            Return;
        }
        if($txt){
           $message = $txt; 
        }
        else{
            Return;
        }
        
        $sent = 0;

        $username = config('app.africa_is_talking_username');
        $apikey = config('app.africa_is_talking_apikey');
        $gateway    = new AfricasTalkingGateway($username, $apikey);

        try {
            $results = $gateway->sendMessage($recipients, $message);
            foreach($results as $result) {
            //We save this response to a table for reporting purposes
                $sms_notice = new Sms_notice;
                $sms_notice->Phone = $result->number;
                $sms_notice->status = $result->status;
                $sms_notice->MessageId = $result->messageId;
                $sms_notice->Cost = $result->cost;
                $sms_notice->txt = $message;
                $sms_notice->save();
 
            } 
        }
        catch (AfricasTalkingGatewayException $e) {
            // echo "Africa is talking Sending Error!: ".$e->getMessage();
            //We save the failed message response for debugging purposes
            $failed_sms_notice = new Failed_sms_notice;
            $failed_sms_notice->phone = $recipients;
            $failed_sms_notice->message = $e->getMessage();
            $failed_sms_notice->txt = $message;
            $failed_sms_notice->save();

            
        }
        

    }
}