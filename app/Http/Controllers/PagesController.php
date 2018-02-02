<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use DB;
use App\Order;
use App\Bid;
use App\Earning;
use App\Message;
use Carbon\Carbon;
use App\User;
use App\Notification;
use App\Http\Controllers\NotificationController;
use Mail;
use Illuminate\Support\Facades\Auth;


class PagesController extends Controller
{


    public static $site_title ='Academic Research Assistants';
    public static $page_title;
    public static $page_description;
    public static $ingine_no;
    public static $list_messages;
    public static $messages_no;
    public static $order_msg_no;
    public static $notifications_no;
    // private $notification_text; We will use the number of notification in the text instead
    public static $list_bid_accepted;
    public static $list_notifications;
    public static $number_tasks;
    // private $tasks_text; We will use the number of tasks in the text string instead
    public static $list_tasks;
    public static $user_description;
    public static $join_date_text;
    public static $version_no= '0.98';



     public function __construct()
    {
        $this->middleware('auth');

    }



    public function dashboard()
    {
         if (Auth::user()->ni_admin )
        {
            $prof_comp_array = 0;
            $list_messages = Message::where('for_admin', 1)->where('unread', 1)->orderBy('created_at', 'desc')->get();
            $messages_no = $list_messages->count();

            $list_bid_accepted = Notification::where('status',0)->where('type',"admin_order_bidPlaced")->orderBy('created_at', 'desc')->get();
            $list_order_message = Notification::where('status',0)->where('type',"admin_order_message")->orderBy('created_at', 'desc')->get();


            // $list_notifications = $list_order_message->toBase()->merge($list_bid_accepted);
            // $notifications_no = $list_bid_accepted->count();

            // I used $list_bid_accepted the same as non-admins so as not to have a difficult time to display them on the layout

            // on 4/10/2016 I changed notifications_no = 0 so as to make sure there are no bid notifications on the side of the admin
            $notifications_no = 0;


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

        $earnings = Earning::all();


        self::$page_description='A snapshort of your Account';
        $orders= Order::orderBy('created_at', 'desc')->get();

    	Return view('pages.dashboard', [
            'site_title' =>self::$site_title,
            'page_title' => self::$page_title='Dashboard',
            'page_description' =>self::$page_description,
            'notifications_no' =>self::$notifications_no,
            'list_notifications' =>self::$list_notifications,
            'number_tasks' =>self::$number_tasks,
            'list_tasks' => self::$list_tasks,
            'user_description' => self::$user_description,
            'join_date_text' => self::$join_date_text,
            'version_no' => self::$version_no,
            'orders'=> $orders,
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

    public function bids()
    {
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

        $orders =Order::where('status', 'Available')->orderBy('created_at', 'desc')->get();

        Return view('pages.read_bids', [
            'site_title' =>self::$site_title,
            'page_title' => self::$page_title='Bids',
            'page_description' =>self::$page_description='All the recent Order bids',
            'notifications_no' =>self::$notifications_no,
            'list_notifications' =>self::$list_notifications,
            'number_tasks' =>self::$number_tasks,
            'list_tasks' => self::$list_tasks,
            'user_description' => self::$user_description,
            'join_date_text' => self::$join_date_text,
            'version_no' => self::$version_no,
            'order_bids'=> $orders  ,
            'messages_no'=> $messages_no,
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

    // We use this function to view all the messages table
    public function mailbox(Request $request)
    {


       if($request->user()->ni_admin)
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

              $messages = Message::where('for_admin',1)
              ->where('unread', 0)
              ->orderBy('created_at', 'desc')
              ->groupBy('subject')
              ->get();

              $unread_msgs = Message::all()->where('for_admin', 1)->where('unread', 1)->sortByDesc('created_at');
            }
        elseif(!$request->user()->ni_admin)
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


                $messages = Message::where('user_id',$request->user()->id)
                  ->where('unread', 0)
                  ->orderBy('created_at', 'desc')
                  ->groupBy('subject')
                  ->get();

                $unread_msgs = $request->user()->messages->where('unread', 1)->sortByDesc('created_at');

          }


        Return view('pages.messages',[
            'site_title' =>self::$site_title,
            'page_title' => self::$page_title='Mailbox',
            'page_description' => $unread_msgs->count().' Unread Messages',
            'notifications_no' =>self::$notifications_no,
            'list_notifications' =>self::$list_notifications,
            'number_tasks' =>self::$number_tasks,
            'list_tasks' => self::$list_tasks,
            'user_description' => self::$user_description,
            'join_date_text' => self::$join_date_text,
            'version_no' => self::$version_no,
            'messages' => $messages,
            'unread_msgs' => $unread_msgs,
            'messages_no'=> $messages_no,
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

    public function sentbox(Request $request)
    {


       if($request->user()->ni_admin)
            {

              $unread_msgs = Message::where('for_admin', 1)->where('unread', 1)->get();

              $messages = Message::where('for_admin',0)
              ->orderBy('created_at', 'desc')
              ->paginate(50);

                $prof_comp_array = 0;
                $list_messages = Message::where('for_admin', 1)->where('unread', 1)->orderBy('created_at', 'desc')->get();
                $messages_no = $list_messages->count();

                $list_bid_accepted = Notification::where('status',0)->where('type',"admin_order_bidPlaced")->orderBy('created_at', 'desc')->get();
                $list_order_message = Notification::where('status',0)->where('type',"admin_order_message")->orderBy('created_at', 'desc')->get();



                $notifications_no = $list_bid_accepted->count();
                // I used $list_bid_accepted the same as other users so as not to have a difficult time to display them on the layout

                $order_msg_no = $list_order_message->count();

                $list_tasks = Notification::where('status',0)->where('type',"admin_order_late")->orderBy('created_at', 'desc')->get();
                $number_tasks =$list_tasks->count() ;
            }
        elseif($request->user()->ni_admin == null)
          {

                $unread_msgs = $request->user()->messages->where('unread', 1)->sortByDesc('created_at')->groupBy('subject');

                $messages = Message::where('sender_id', $request->user()->id)
              ->orderBy('created_at', 'desc')
              ->paginate(50);

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

        Return view('pages.messages_sent',[
            'site_title' =>self::$site_title,
            'page_title' => self::$page_title='Sent',
            'page_description' => $unread_msgs->count().' Unread Messages',
            'notifications_no' =>self::$notifications_no,
            'list_notifications' =>self::$list_notifications,
            'number_tasks' =>self::$number_tasks,
            'list_tasks' => self::$list_tasks,
            'user_description' => self::$user_description,
            'join_date_text' => self::$join_date_text,
            'version_no' => self::$version_no,
            'messages' => $messages,
            'unread_msgs' => $unread_msgs,
            'messages_no'=> $messages_no,
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

    public function readmail(Request $request, Message $message)
    {
        if(!$request->user()->ni_admin)
            {
                if($message->user_id !== $request->user()->id && $message->sender_id !== $request->user()->id ){
                    Return redirect('mailbox')->with('error',"You don't have permision to view that message, Kindly contact support if you need further assistance");
                }
            }

        if($request->user()->ni_admin)
            {

              if($message->for_admin)
              {
                $inbox_thread = Message::where('subject', $message->subject)->where('sender_id', $message->sender_id)->get();
                $sent_thread = Message::where('subject',$message->subject)->where('user_id', $message->sender_id)->get();
                $message->unread = 0;
                $message->update();
              }
              else
              {
                $sent_thread = Message::where('subject', $message->subject)->where('sender_id', $message->user_id)->get();
                $inbox_thread = Message::where('subject',$message->subject)->where('user_id', $message->user_id)->get();
              }


              $prof_comp_array = 0;
              $list_messages = Message::where('for_admin', 1)->where('unread', 1)->orderBy('created_at', 'desc')->get();
              $messages_no = $list_messages->count();

              $list_bid_accepted = Notification::where('status',0)->where('type',"admin_order_bidPlaced")->orderBy('created_at', 'desc')->get();
              $list_order_message = Notification::where('status',0)->where('type',"admin_order_message")->orderBy('created_at', 'desc')->get();


              $notifications_no = $list_bid_accepted->count();
              // I used $list_bid_accepted the same as other users so as not to have a difficult time to display them on the layout

              $order_msg_no = $list_order_message->count();

              $list_tasks = Notification::where('status',0)->where('type',"admin_order_late")->orderBy('created_at', 'desc')->get();
              $number_tasks =$list_tasks->count() ;

              $unread_msgs = Message::where('for_admin', 1)->where('unread', 1)->get();
            }
        elseif($request->user()->ni_admin == null)
          {

                $sent_thread = Message::where('subject', $message->subject)->where('user_id', $request->user()->id)->get();
                $inbox_thread = Message::where('subject',$message->subject)->where('sender_id', $request->user()->id)->get();

                if($request->user()->id == $message->user_id ){
                    $message->unread = 0;
                     $message->update();
                }

                $prof_comp_array = NotificationController::profileComplete(Auth::user());

                $list_messages = Auth::user()->messages->where('unread',1)->sortByDesc('created_at');
                $messages_no = $list_messages->count();

                $list_bid_accepted = Auth::user()->notifications()->where('status',0)->where('type','order_bid_accepted')->orderBy('created_at', 'desc')->get();
                $list_order_message = Auth::user()->notifications()->where('status',0)->where('type','order_message')->orderBy('created_at', 'desc')->get();

                $notifications_no = $list_bid_accepted->count();
                $order_msg_no = $list_order_message->count();

                $list_order_late = Auth::user()->notifications()->where('status',0)->where('type','order_late')->orderBy('created_at', 'desc')->get();
                $list_order_revisions = Auth::user()->notifications()->where('status',0)->where('type','order_revision')->orderBy('created_at', 'desc')->get();

                $list_tasks = $list_order_revisions->toBase()->merge($list_order_late);
                $number_tasks =$list_tasks->count() ;


                $unread_msgs = $request->user()->messages->where('unread', 1)->sortByDesc('created_at')->groupBy('subject');
          }

          $mail_thread = $sent_thread->toBase()->merge($inbox_thread)->sortBy('created_at');

          $mail_thread = $mail_thread->values()->all();

        Return view('pages.messages_read',[
            'site_title' =>self::$site_title,
            'page_title' => self::$page_title='Read Mail',
            'page_description' => $unread_msgs->count().' Unread Messages',
            'notifications_no' =>self::$notifications_no,
            'list_notifications' =>self::$list_notifications,
            'number_tasks' =>self::$number_tasks,
            'list_tasks' => self::$list_tasks,
            'user_description' => self::$user_description,
            'join_date_text' => self::$join_date_text,
            'version_no' => self::$version_no,
            'message'=> $message,
            'unread_msgs' => $unread_msgs,
            'mail_thread' => $mail_thread,
            // 'from_email' => $from_email,
            // 'from_name' => $from_name,
            'messages_no'=> $messages_no,
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


    public function store_reply(Request $request)
    {
        $this->validate($request,
            ['subject'=>'required',
            'receiver_id' =>'required',
            ]);
        $original_mail= Message::findOrFail($request->mail_id);
        $message = new Message;
        $message->subject = $request->subject;
        $message->sender_id = $request->user()->id;

        //We receiver_id is the same as the sender_id.
        //If they are the same it means that this user is sending a message to themslves.
        // So we check the original mail that we are responding to and check who the user_id for that message.
        // That user_id is the one that we set as the receiver_id;
        if($request->receiver_id == $request->user()->id)
        {
            $request->receiver_id = $original_mail->user_id;
        }

        $message->user_id = $request->receiver_id;
        $message->body = $request->body;
        $message->department = $request->department;

        if(!$request->user()->ni_admin){
            $message->for_admin = 1;
        }

        $message->save();

        $reply_id = $message->id;

        $user = User::findOrFail($request->receiver_id);
        if($user->ni_admin){
            if ($request->department == 'Support') {
                    # code...
                    $user->email = 'support@academicresearchassistants.com';
                    $user->first_name = 'Support Department';
                }

                if ($request->department == 'Quality Assurance') {
                    # code...
                    $user->email = 'qualityassurance@academicresearchassistants.com';
                    $user->first_name = 'Quality Assurance Department';
                }

                if ($request->department == 'Billing') {
                    # code...
                    $user->email = 'billing@academicresearchassistants.com';
                    $user->first_name = 'Billing Department';

                }
        }
        $subject = $request->subject;
        $body = $request->body;
        Mail::queue('emails.new_reply_message',['user'=>$user,  'subject'=>$subject, 'body'=>$body, 'reply_id'=>$reply_id], function ($m) use ($user, $subject)
        {
            $m->from('notifications@academicresearchassistants.com','Do not Reply: Academic Research Assistants');

            $m->to($user->email, $user->first_name)->subject('New Message on '.$subject);
        });

        Return redirect('sentbox')->with('message', 'Reply posted successfully');
    }

    public function compose(Request $request)
    {
        if($request->user()->ni_admin)
            {
                    $unread_msgs = Message::where('for_admin', 1)->where('unread', 1)->get();
                    $writers = User::where('ni_admin', null)->where('status', '1')->where('verified',1)->orderBy('first_name')->get();

                    $prof_comp_array = 0;
                    $list_messages = Message::where('for_admin', 1)->where('unread', 1)->orderBy('created_at', 'desc')->get();
                    $messages_no = $list_messages->count();

                    $list_bid_accepted = Notification::where('status',0)->where('type',"admin_order_bidPlaced")->orderBy('created_at', 'desc')->get();
                    $list_order_message = Notification::where('status',0)->where('type',"admin_order_message")->orderBy('created_at', 'desc')->get();



                    $notifications_no = $list_bid_accepted->count();
                    // I used $list_bid_accepted the same as other users so as not to have a difficult time to display them on the layout

                    $order_msg_no = $list_order_message->count();

                    $list_tasks = Notification::where('status',0)->where('type',"admin_order_late")->orderBy('created_at', 'desc')->get();
                    $number_tasks =$list_tasks->count() ;
            }
        elseif($request->user()->ni_admin == null)
          {

                $unread_msgs = $request->user()->messages->where('unread', 1)->sortByDesc('created_at')->groupBy('subject');
                $writers = 0;

                $prof_comp_array = NotificationController::profileComplete(Auth::user());

                $list_messages = Auth::user()->messages->where('unread',1)->sortByDesc('created_at');
                $messages_no = $list_messages->count();

                $list_bid_accepted = Auth::user()->notifications()->where('status',0)->where('type','order_bid_accepted')->orderBy('created_at', 'desc')->get();
                $list_order_message = Auth::user()->notifications()->where('status',0)->where('type','order_message')->orderBy('created_at', 'desc')->get();

                $notifications_no = $list_bid_accepted->count();
                $order_msg_no = $list_order_message->count();

                $list_order_late = Auth::user()->notifications()->where('status',0)->where('type','order_late')->orderBy('created_at', 'desc')->get();
                $list_order_revisions = Auth::user()->notifications()->where('status',0)->where('type','order_revision')->orderBy('created_at', 'desc')->get();

                $list_tasks = $list_order_revisions->toBase()->merge($list_order_late);
                $number_tasks =$list_tasks->count() ;
          }

        Return view('pages.compose',[
            'site_title' =>self::$site_title,
            'page_title' => self::$page_title='Compose',
            'page_description' => $unread_msgs->count().' Unread Messages',
            'version_no' => self::$version_no,
            'writers' => $writers,
            'messages_no'=> $messages_no,
            'list_messages' => $list_messages,
            'list_order_message' => $list_order_message,
            'order_msg_no' => $order_msg_no,
            'notifications_no' => $notifications_no,
            'list_bid_accepted' => $list_bid_accepted,
            'list_tasks' => $list_tasks,
            'number_tasks' => $number_tasks,
            'prof_comp_array' => $prof_comp_array,
            ]);

    }


    public function newMail(Request $request)
    {

        $this->validate($request,
            ['subject'=>'required',
            'department' =>'required',
            'body'=>'required',
            ]);
        $user_request = $request->user();

        // We confirm that the user isn't an admin
        // and make the message to be sent to admins

        if (!$user_request->ni_admin)
            {
                $message = new Message;
                $message->subject = $request->subject;
                $message->sender_id = $user_request->id;
                $message->department = $request->department;
                $message->user_id = 0;
                $message->body = $request->body;
                $message->for_admin = 1;
                $message->save();

                if ($request->department == 'Support') {
                    # code...
                    $email_to = 'support@academicresearchassistants.com';
                    $email_name = 'Support Department';
                }

                if ($request->department == 'Quality Assurance') {
                    # code...
                    $email_to = 'qualityassurance@academicresearchassistants.com';
                    $email_name = 'Quality Assurance Department';
                }

                if ($request->department == 'Billing') {
                    # code...
                    $email_to = 'billing@academicresearchassistants.com';
                    $email_name = 'Billing Department';

                }

                    $subject = 'New '.$request->department.' Issue:: '.$request->subject;
                    $body = $request->body;
                    $department = $request->department;
                    Mail::send('emails.new_mail_writer_toadmin',['user_request'=>$user_request, 'mail'=>$message, 'subject'=>$subject, 'body'=>$body, 'department'=>$department],function ($m) use ($email_name, $email_to, $subject)
                    {
                        $m->from('notifications@academicresearchassistants.com','Do not Reply: Academic Research Assistants');

                        $m->to($email_to, $email_name)->subject($subject);
                    });
                return redirect('sentbox')->with ('message','Message to '.$department .' department sent successfully');

            }
        elseif($request->user()->ni_admin)
            {
                if($request->writer == "all writers")
                {

                    $writers = User::where('ni_admin', null)->where('status', '1')->where('verified',1)->get();
                    foreach($writers as $writer)
                    {
                        $message = new Message;
                        $message->subject = $request->subject;
                        $message->sender_id = $request->user()->id;
                        $message->user_id = $writer->id;
                        $message->body = $request->body;
                        $message->department = $request->department;
                        $message->save();

                        // We queue mail to send notifications
                        $subject = $request->subject;
                        $mail = $message;
                        Mail::queue('emails.new_message',['user'=>$writer, 'subject'=>$subject, 'mail'=>$mail],function ($m) use ($writer,$subject)
                        {
                            $m->from('notifications@academicresearchassistants.com','Do not Reply: Academic Research Assistants');

                            $m->to($writer->email, $writer->first_name)->subject($subject);
                        });
                    }
                    return redirect('sentbox')->with ('message','Message to All writers sent successfully');
                }
                elseif($request->writer == "active writers")
                {
                    $writers = User::where('ni_admin', null)->get();
                    foreach($writers as $writer)
                    {
                        if ($writer->bids()->count()) {

                            $message = new Message;
                            $message->subject = $request->subject;
                            $message->sender_id = $request->user()->id;
                            $message->user_id = $writer->id;
                            $message->body = $request->body;
                            $message->department = $request->department;
                            $message->save();

                            // We queue mail to send notifications
                            $subject = $request->subject;
                            $mail = $message;
                            Mail::queue('emails.new_message',['user'=>$writer, 'subject'=>$subject, 'mail'=>$mail],function ($m) use ($writer,$subject)
                            {
                                $m->from('notifications@academicresearchassistants.com','Do not Reply: Academic Research Assistants');

                                $m->to($writer->email, $writer->first_name)->subject($subject);
                            });
                        }

                    }
                    return redirect('sentbox')->with ('message','Message to Active writers sent successfully');
                }

                elseif($request->writer == "inactive writers")
                {
                    $writers = User::where('ni_admin', null)->get();
                    foreach($writers as $writer)
                    {
                        if (!$writer->bids()->count()) {

                            $message = new Message;
                            $message->subject = $request->subject;
                            $message->sender_id = $request->user()->id;
                            $message->user_id = $writer->id;
                            $message->body = $request->body;
                            $message->department = $request->department;
                            $message->save();

                            // We queue mail to send notifications
                            $subject = $request->subject;
                            $mail = $message;
                            Mail::queue('emails.new_message',['user'=>$writer, 'subject'=>$subject, 'mail'=>$mail],function ($m) use ($writer,$subject)
                            {
                                $m->from('notifications@academicresearchassistants.com','Do not Reply: Academic Research Assistants');

                                $m->to($writer->email, $writer->first_name)->subject($subject);
                            });
                        }

                    }
                    return redirect('sentbox')->with ('message','Message to Inactive writers sent successfully');
                }
                else
                {
                    $writer = User::findOrFail($request->writer);
                    $message = new Message;
                    $message->subject = $request->subject;
                    $message->sender_id = $request->user()->id;
                    $message->user_id = $request->writer;
                    $message->body = $request->body;
                    $message->department = $request->department;
                    $message->save();

                    // We queue mail to send notifications
                    $subject = $request->subject;
                    $mail = $message;
                    Mail::queue('emails.new_message',['user'=>$writer, 'subject'=>$subject, 'mail'=>$mail],function ($m) use ($writer,$subject)
                    {
                        $m->from('notifications@academicresearchassistants.com','Do not Reply: Academic Research Assistants');

                        $m->to($writer->email, $writer->first_name)->subject($subject);
                    });

                    return redirect('sentbox')->with ('message','Message to '.$writer->first_name.' sent successfully');
                }

            }


    }

    public function allusers()
    {
        $users = User::where('ni_admin', null)->where('verified', 1)->where('status', "1")->get();
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



        Return view('pages.users',[
            'site_title' =>self::$site_title,
            'page_title' => self::$page_title='Users',
            'page_description' =>self::$page_description='A snapshort of all users and their orders',
            'notifications_no' =>self::$notifications_no,
            'list_notifications' =>self::$list_notifications,
            'number_tasks' =>self::$number_tasks,
            'list_tasks' => self::$list_tasks,
            'version_no' => self::$version_no,
            'users' => $users,
            'messages_no'=> $messages_no,
            'list_messages' => $list_messages,
            'list_order_message' => $list_order_message,
            'order_msg_no' => $order_msg_no,
            'notifications_no' => $notifications_no,
            'list_bid_accepted' => $list_bid_accepted,
            'list_tasks' => $list_tasks,
            'number_tasks' => $number_tasks,
            'prof_comp_array' => $prof_comp_array,
            ]);
    }


    public function profile(Request $request)
    {
        $user = $request -> user();

            $b_name = '';
            $b_b_name = '';
            $a_name = '';
            $a_number = '';

        if($user->b_details->count() > 0)
        {
            $b_name = $user->b_details->first()->b_name;
            $b_b_name = $user->b_details->first()->b_b_name;
            $a_name = $user->b_details->first()->a_name;
            $a_number = $user->b_details->first()->a_number;
        }


        $subject_infos = DB::table('orders')->where('user_id',$user->id)->where('approved', 1)->select('subject', DB::raw('count(*) as total'))->groupBy('subject')->orderBy('total','desc')->get();

          $s_d = $user->earnings()->count() - $user->fines()->count();
          if (!$s_d) {
            $rating_100 = 0;
            $rating_5 = 0;
            }
          else {
            $r = $s_d/$user->earnings()->count();
            $rating_5 = round($r*5,2);
            $rating_100 = round($r*100,2);
          }  

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

        Return view('pages.profile',[
            'site_title'=> self::$site_title,
            'page_title' => self::$page_title='User Profile',
            'page_description' =>self::$page_description='Your profile details',
            'notifications_no' =>self::$notifications_no,
            'list_notifications' =>self::$list_notifications,
            'number_tasks' =>self::$number_tasks,
            'list_tasks' => self::$list_tasks,
            'list_notifications' => self::$list_notifications,
            'version_no' => self::$version_no,
            'user' => $user,
            'subject_infos' => $subject_infos,
            'b_name' => $b_name,
            'b_b_name' => $b_b_name,
            'a_name'=>$a_name,
            'a_number' => $a_number,
            'messages_no'=> $messages_no,
            'list_messages' => $list_messages,
            'list_order_message' => $list_order_message,
            'order_msg_no' => $order_msg_no,
            'notifications_no' => $notifications_no,
            'list_bid_accepted' => $list_bid_accepted,
            'list_tasks' => $list_tasks,
            'number_tasks' => $number_tasks,
            'prof_comp_array' => $prof_comp_array,
            'rating_5' => $rating_5,
            'rating_100' => $rating_100,
            ]);
    }


    public function viewWriterProfile(Request $request, User $user)
    {
        // We confirm that the person requesting the user's profile is an admin or client (should come later on its time)
        // it has finally arrived (2018/jan/)
        if(! $request->user()->ni_admin){
            // return back()->with ('error', 'You are not authorised to view that page. Contact support for assistance');
            Return redirect('find_work')->with('error',"We are still working on a new writer profile page in the mean time... Find some work below :)");
        }

            $prof_comp_array = 0;
            $list_messages = Message::where('for_admin', 1)->where('unread', 1)->orderBy('created_at', 'desc')->get();
            $messages_no = $list_messages->count();

            // $list_bid_accepted = Notification::where('status',0)->where('type',"admin_order_bidPlaced")->orderBy('created_at', 'desc')->get();
            // I have disabled the need to show bid notifications on the writers profile page because they are many and only the client should see this.

            $list_bid_accepted = 0;
            $list_order_message = Notification::where('status',0)->where('type',"admin_order_message")->orderBy('created_at', 'desc')->get();


            // $list_notifications = $list_order_message->toBase()->merge($list_bid_accepted);
            $notifications_no = 0;
            // I used $list_bid_accepted the same as other users so as not to have a difficult time to display them on the layout

            $order_msg_no = $list_order_message->count();

            // $list_notifications = $list_notifications->sortByDesc('created_at');

            $list_tasks = Notification::where('status',0)->where('type',"admin_order_late")->orderBy('created_at', 'desc')->get();
            $number_tasks =$list_tasks->count() ;

            $b_name = '';
            $b_b_name = '';
            $a_name = '';
            $a_number = '';
            //We count fines and 5 star ratings
            $s_d = $user->earnings()->count() - $user->fines()->count();
            if (!$s_d) {
              $rating_5 = 0;
              $rating_100 =0;
            }
            else {
                $rating = $s_d/$user->earnings()->count();
                $rating_5 = round($rating*5,2);
                $rating_100 = round($rating*100,2);
            }
        if($user->b_details->count() > 0)
        {
            $b_name = $user->b_details->first()->b_name;
            $b_b_name = $user->b_details->first()->b_b_name;
            $a_name = $user->b_details->first()->a_name;
            $a_number = $user->b_details->first()->a_number;
        }

        $subject_infos = DB::table('orders')->where('user_id',$user->id)->where('approved', 1)->select('subject', DB::raw('count(*) as total'))->groupBy('subject')->orderBy('total','desc')->get();

        Return view('pages.profile',[
            'site_title'=> self::$site_title,
            'page_title' => self::$page_title= $user->first_name."'s Account",
            'page_description' =>self::$page_description= $user->first_name."'s Account details",
            'notifications_no' =>self::$notifications_no,
            'list_notifications' =>self::$list_notifications,
            'number_tasks' =>self::$number_tasks,
            'list_tasks' => self::$list_tasks,
            'version_no' => self::$version_no,
            'user' => $user,
            'subject_infos' => $subject_infos,
            'b_name' => $b_name,
            'b_b_name' => $b_b_name,
            'a_name'=>$a_name,
            'a_number' => $a_number,
            'list_order_message' => $list_order_message,
            'messages_no'=> $messages_no,
            'order_msg_no' => $order_msg_no,
            'list_messages' => $list_messages,
            'notifications_no' => $notifications_no,
            'list_bid_accepted' => $list_bid_accepted,
            'list_tasks' => $list_tasks,
            'number_tasks' => $number_tasks,
            'prof_comp_array' => $prof_comp_array,
            'rating_5' => $rating_5,
            'rating_100' => $rating_100,
            ]);

    }

    public function fines_policy()
    {
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

        Return view('pages.finesPolicy',[
            'site_title'=> self::$site_title,
            'page_title' => self::$page_title='Fines Policy',
            'page_description' =>self::$page_description='Our Fines Policy',
            'notifications_no' =>self::$notifications_no,
            'list_notifications' =>self::$list_notifications,
            'number_tasks' =>self::$number_tasks,
            'list_tasks' => self::$list_tasks,
            'list_notifications' => self::$list_notifications,
            'version_no' => self::$version_no,
           'list_order_message' => $list_order_message,
            'messages_no'=> $messages_no,
            'order_msg_no' => $order_msg_no,
            'list_messages' => $list_messages,
            'notifications_no' => $notifications_no,
            'list_bid_accepted' => $list_bid_accepted,
            'list_tasks' => $list_tasks,
            'number_tasks' => $number_tasks,
            'prof_comp_array' => $prof_comp_array,
            ]);
    }
    // public function activateUsers()
    // {
    //     $users = User::all()->where('status',null);
    //     foreach ($users as $user)
    //         {
    //             $user->status = '1';
    //             $user->verified = 1;
    //             $user->update();
    //         }
    //     return 'All is well Brother';
    // }
}
