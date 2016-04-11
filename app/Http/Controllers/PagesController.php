<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Order;
use App\Bid;
use App\Earning;
use App\Message;
use Carbon\Carbon;


class PagesController extends Controller
{
     public function __construct()
    {
        $this->middleware('auth');

    }


    public static $site_title ='Academic Research Assistants';
    public static $page_title;
    public static $page_description;
    public static $notifications_no= '0';
    // private $notification_text; We will use the number of notification in the text instead
    public static $list_notifications= '';
    public static $number_tasks ='0';
    // private $tasks_text; We will use the number of tasks in the text string instead
    public static $list_tasks='';
    public static $user_description='I take my Work seriously';
    public static $join_date_text='';
    public static $version_no= '0.0';






    public function dashboard()
    {
    	
        $earnings = Earning::all();

        
        self::$page_description='A snapshort of your Account';
        $orders= Order::orderBy('created_at', 'desc')->paginate(5);

    	Return view('pages.dashboard', [
            'site_title' =>self::$site_title,
            'page_title' => self::$page_title='Dashboard',
            'page_description' =>self::$page_description,
            'notifications_no' =>self::$notifications_no,
            'list_notifications' =>self::$list_notifications,
            'number_tasks' =>self::$number_tasks,
            'list_tasks' => self::$list_tasks,
            'list_notifications' => self::$list_notifications,
            'user_description' => self::$user_description,
            'join_date_text' => self::$join_date_text,
            'version_no' => self::$version_no,
            'orders'=> $orders,

            ]);

    }
    public function bids()
    {
        $orders =Order::where('status', 'Available')->orderBy('created_at', 'desc')->get();

        Return view('pages.read_bids', [
            'site_title' =>self::$site_title,
            'page_title' => self::$page_title='Bids',
            'page_description' =>self::$page_description='All the recent Order bids',
            'notifications_no' =>self::$notifications_no,
            'list_notifications' =>self::$list_notifications,
            'number_tasks' =>self::$number_tasks,
            'list_tasks' => self::$list_tasks,
            'list_notifications' => self::$list_notifications,
            'user_description' => self::$user_description,
            'join_date_text' => self::$join_date_text,
            'version_no' => self::$version_no,
            'orders'=> $orders  ,

            ]);
    }
    // We use this function to view all the messages table
    public function mailbox()
    {
        $messages = Message::orderBy('created_at', 'desc')->get();

        Return view('pages.messages',[
            'site_title' =>self::$site_title,
            'page_title' => self::$page_title='Mailbox',
            'page_description' =>self::$page_description='All recent messages',
            'notifications_no' =>self::$notifications_no,
            'list_notifications' =>self::$list_notifications,
            'number_tasks' =>self::$number_tasks,
            'list_tasks' => self::$list_tasks,
            'list_notifications' => self::$list_notifications,
            'user_description' => self::$user_description,
            'join_date_text' => self::$join_date_text,
            'version_no' => self::$version_no,
            'messages'=> $messages,

            ]);
    }
    public function sentbox()
    {
        $messages = Message::orderBy('created_at', 'desc')->get();

        Return view('pages.messages',[
            'site_title' =>self::$site_title,
            'page_title' => self::$page_title='Sentbox',
            'page_description' =>self::$page_description='All sent messages',
            'notifications_no' =>self::$notifications_no,
            'list_notifications' =>self::$list_notifications,
            'number_tasks' =>self::$number_tasks,
            'list_tasks' => self::$list_tasks,
            'list_notifications' => self::$list_notifications,
            'user_description' => self::$user_description,
            'join_date_text' => self::$join_date_text,
            'version_no' => self::$version_no,
            'messages'=> $messages,

            ]);
    }
    public function readmail(Message $message)
    {
        Return view('pages.messages',[
            'site_title' =>self::$site_title,
            'page_title' => self::$page_title='Read Mail',
            'page_description' =>self::$page_description='',
            'notifications_no' =>self::$notifications_no,
            'list_notifications' =>self::$list_notifications,
            'number_tasks' =>self::$number_tasks,
            'list_tasks' => self::$list_tasks,
            'list_notifications' => self::$list_notifications,
            'user_description' => self::$user_description,
            'join_date_text' => self::$join_date_text,
            'version_no' => self::$version_no,
            'message'=> $message,
            ]);
    }
    public function store_reply(Request $request)
    {
        $this->validate($request,
            ['subject'=>'required',
            'user_id'=>'required',
            ]); 
        $message = new Message;
        $message->subject = $request->subject;
        $message->sender_id = $request->user_id;
        $message->user_id = $request->receiver_id;
        $message->body = $request->body;

        $message->save();

        return back()->with('message', 'Reply posted successfully');
    }
}
