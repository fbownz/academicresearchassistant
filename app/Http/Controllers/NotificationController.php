<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use App\Order;
use App\Notification;
use Mail;
use App\Http\Controllers\PagesController;
use App\Bid;
use App\Sms_notice;
use App\Failed_sms_notice;

//We add the AfricasTalkingGateway in order to send SMS notifications
use App\AfricasTalkingGateway; 
use Carbon\Carbon;



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
            $m->from('notifications@academicresearchassistants.com','Do not Reply: Academic Research Assistants');

            $m->to($user->email, $user->first_name)->subject('Academicresearch: Congrats! Your bid has been accepted on order '.$order->order_no);
        });

        
        //We send an sms notification to the User for the assigned order;
        $txt = 'Congratulations '.$user->first_name.' your bid for Order '.$order->order_no.' has been accepted. Deliver the paper by '.$deadline."\n".' academicresearchassistants.com';
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
            $m->from('notifications@academicresearchassistants.com','Do not Reply: Academic Research Assistants');

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

        Mail::queue('emails.order_message',['user'=>$user,  'order'=>$order],function ($m) use ($user, $order)
        {
            $m->from('notifications@academicresearchassistants.com','Do not Reply: Academic Research Assistants');

            $m->to($user->email, $user->first_name)->subject('Academicresearch: You have a New message on your order '.$order->order_no);
        });

        //We send an sms notification to the User for order Message;
        $txt = 'You have a new Message on Order '.$order->order_no.' Kindly check the order to respond'."\n".' academicresearchassistants.com';
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

            Mail::queue('emails.admin_order_message',['order'=>$order],function ($m) use ($admin_email, $admin_name, $order)
        {
            $m->from('notifications@academicresearchassistants.com','Do not Reply: Academic Research Assistants');

            $m->to($admin_email, $admin_name)->subject('Academicresearch: You have a New message on order '.$order->order_no);
        });
        }   

    }

    public static function orderDeliveryNotice(User $user, Order $order)
    {

        Mail::queue('emails.order_delivery',['user'=>$user, 'order'=>$order], function($m)use ($user, $order)
        {
            $m->from('notifications@academicresearchassistants.com', 'Do not Reply: Academic Research Assistants');

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
            $m->from('notifications@academicresearchassistants.com','Do not Reply: Academic Research Assistants');

            $m->to($admin_email, $admin_name)->subject('Academicresearch: Order #'.$order->order_no.' has been delivered');
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
            $m->from('notifications@academicresearchassistants.com', 'Do not Reply: Academic Research Assistants');

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
                $m->from('notifications@academicresearchassistants.com','Do not Reply: Academic Research Assistants');

                $m->to($admin_email, $admin_name)->subject('Academicresearch: Late Order Notice! for Order '.$order->order_no);
            });
        }

        //We send an sms notification to the User for the late order;
        $txt = 'Order '.$order->order_no.' is late. A late fee fine has been applied. Kindly upload it as soon as possible to avoid futher penalties.  Academicresearchasistants.com';
        $send_sms =self::sendSMSnotice($user,$txt);

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
            $m->from('notifications@academicresearchassistants.com', 'Do not Reply: Academic Research Assistants');

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
        $users_to_be_assigned = User::whereRaw('verified = 1 and ni_admin = 0')->get();

        $bids_submitted = Bid::where('order_id','=',$order->id)->orderBy('created_at','desc')->get();

        $notification->status = 1;
        $notification->update();

         return view('pages.order', [ 
        'site_title' => PagesController::$site_title,
        'page_title' => $order->order_no,
        'page_description' =>PagesController::$page_description='Order details',
        
        
        'user_description' => PagesController::$user_description,
        'join_date_text' => PagesController::$join_date_text,
        'version_no' => PagesController::$version_no,
        'order' => $order,
        'writers' => $users_to_be_assigned,
        'bids' => $bids_submitted ]);
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
