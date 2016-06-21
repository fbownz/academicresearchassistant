<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Order;
use App\User;
use App\Bid;
use Carbon\Carbon;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\Order_delivery_reports;
use App\Http\Controllers\OrderreportsController;
use App\Http\Controllers\NotificationController;

class OrdersController extends Controller
{
    //     public function register()
    // {
    //     return view('auth.register');
    // }
    
     public function __construct()
    {
        $this->middleware('auth');
    }
    

 public function index(){
 	// I set the maximum number of orders one can view per page to 10
    $orders= Order::all();

    //We determine if an order is late and we mark is as late by updating their late_field to 1
    // On Fri 27th May I removed "&& $order->status != "Active-Revision"" so as to also update the time on active-revision orders
    foreach ($orders as $order) 
    {
        if ($order->status != "Completed" && $order->status != "Delivered"  && $order->status != "Available") {
            if ( $order->deadline < Carbon::now() && $order->is_late == null) {
                
                $order->is_late = 1;


                $order->update();

                if ($order->user()->count()) 
                {
                 
                NotificationController::lateOrdersNotice($order->user,$order);   
                }
                
            }
            elseif ($order->deadline > Carbon::now() && $order->is_late != null) {
                $order->is_late = null;
                $order->update();
            }
        }
        
    }




 	return view('pages.orders-table',[ 
 		'site_title' => PagesController::$site_title,
        'page_title' => PagesController::$page_title='Orders',
        'page_description' =>PagesController::$page_title='A snapshot of all the recent orders',
       	'notifications_no' =>PagesController::$notifications_no,
        'list_notifications' =>PagesController::$list_notifications,
        'number_tasks' =>PagesController::$number_tasks,
        'list_tasks' => PagesController::$list_tasks,
        
        
        'user_description' => PagesController::$user_description,
        'join_date_text' => PagesController::$join_date_text,
        'version_no' => PagesController::$version_no,
        
        'orders' => $orders]);
 }
     public function sort(Request $request)
     {
         $orderBy = [
         'order_no','status','created_at','total','deadline'];

         if (in_array($request->sort, $orderBy)) {
            $sortBy = $request->sort;

            $orders =Order::orderBy($sortBy,'desc')->paginate(50);

          

             return view('pages.orders-table',[ 
            'site_title' => PagesController::$site_title,
            'page_title' => PagesController::$page_title='Orders',
            'page_description' =>PagesController::$page_title='A snapshot of all the recent orders',
            'notifications_no' =>PagesController::$notifications_no,
            'list_notifications' =>PagesController::$list_notifications,
            'number_tasks' =>PagesController::$number_tasks,
            'list_tasks' => PagesController::$list_tasks,
            
            
            'user_description' => PagesController::$user_description,
            'join_date_text' => PagesController::$join_date_text,
            'version_no' => PagesController::$version_no,
            
            'orders' => $orders]);
        }  
         return back()->with('error','Are you Sure contact @fbownz n let him know what you were doing before you got this message');

     }

    public function show(Request $request, Order $order){
        if (!$request->user()->ni_admin && $order->status !== 'Available' ) {
            if ($order->user->id !== $request->user()->id) {
                Return redirect('orders')->with('error','You do not have permision to view that Order. Contact Support for further assistance');
            }
        }



        $users_to_be_assigned = User::where('verified', 1)->where('status','1')->where('ni_admin', null)->get();
        $bids_submitted = Bid::where('order_id','=',$order->id)->orderBy('created_at','desc')->get();

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



    // return $card;
}
    public function new_order()
    {
        $users = User::whereRaw('status = 1 and ni_admin = 0')->get();
     return view('pages.new_order', [ 
    'site_title' => PagesController::$site_title,
    'page_title' => 'Add New order',
    'page_description' =>PagesController::$page_description='Create a New Order record',
    'notifications_no' =>PagesController::$notifications_no,
    'list_notifications' =>PagesController::$list_notifications,
    'number_tasks' =>PagesController::$number_tasks,
    'list_tasks' => PagesController::$list_tasks,
    'list_notifications' => PagesController::$list_notifications,
    
    
    'user_description' => PagesController::$user_description,
    'join_date_text' => PagesController::$join_date_text,
    'version_no' => PagesController::$version_no,
    'writers' => $users]);   
    }

    public function store_order(Request $request)
    {

        $this->validate($request,
            ['order_no'=>'required|unique:orders,order_no',
            'type_of_product'=>'required',
            ]); 
        $order = new Order;
        $order->type_of_product = $request->type_of_product;
        $order->subject = $request->subject;
        $order->word_length = $request->word_length;
        $order->spacing = $request->spacing;
        $order->academic_level = $request->academic_level;

        $order->delivery_time = $request->delivery_time;
        $order->client_delivery_time =$request->client_deadline;
        // $client_hours =$request->client_deadline;
        // $new_deadline = date("Y-m-d H:i:s",strtotime(sprintf("+%d hours", $hours)));
        // $today= Carbon::now();

        $order->deadline = Carbon::now()->addHours($request->delivery_time);

        
        $order->client_deadline = Carbon::now()->addHours($request->client_deadline);
        
        $order->compensation = $request->compensation;
        $order->total = $request->total;
        $order->style = $request->style;
        $order->no_of_sources = $request->no_of_sources;
        $order->title = $request->title;
        $order->instructions = $request->instructions;
        $order->essential_sources = $request->essential_sources;
        $order->suggested_sources = $request->suggested_sources;
        $order->order_no = $request->order_no;
        $order->attachment = $request->attachment;
        $order->status = $request->status;
        $order->user_id = $request->writer;


        $order->save();

        // We get the order id and the user_id so that we can save it on the order status reports
        $order->order_id = $order->id;
        $order->user_id = $request->user_id;
        OrderreportsController::store_report($order);

        return redirect('orders/'.$order->id)->with('message','Order has been Added Successfully');
            // These are other ways we can populate the request data to the database
        // $order->create([
        //     'type_of_product' => $request->type_of_product;
        //     etc


        //     ])

        // $order->create($request->all());
        // But to use the above code, you have to specify the mass fillable fields in the Card model
    }
    public function edit_order(Order $order)
    {
        $users = User::where('ni_admin',null)->where('status', '1')->where('verified',1)->get();
        return view('pages.new_order', [ 
    'site_title' => PagesController::$site_title,
    'page_title' => 'Edit Order',
    'page_description' =>PagesController::$page_description='',
    'notifications_no' =>PagesController::$notifications_no,
    'list_notifications' =>PagesController::$list_notifications,
    'number_tasks' =>PagesController::$number_tasks,
    'list_tasks' => PagesController::$list_tasks,
    'list_notifications' => PagesController::$list_notifications,
    
    
    'user_description' => PagesController::$user_description,
    'join_date_text' => PagesController::$join_date_text,
    'version_no' => PagesController::$version_no,
    'writers' => $users,
    'order' => $order]);  
    }
    public function update(Request $request, Order $order)
    {

        $this->validate($request,[
            'order_no'=>'required',
            'order_id'=>'required'
            ]); 

        $order->type_of_product = $request->type_of_product;
        $order->subject = $request->subject;
        $order->word_length = $request->word_length;
        $order->spacing = $request->spacing;
        $order->academic_level = $request->academic_level;
        $hours= $request->delivery_time;
        // $new_deadline = date("Y-m-d H:i:s",strtotime(sprintf("+%d hours", $hours)));
        $order->deadline = $order->created_at-> addHours($hours);  
        $order->delivery_time = $request->delivery_time;
        $order->compensation = $request->compensation;
        $order->total = $request->total;
        $order->style = $request->style;
        $order->no_of_sources = $request->no_of_sources;
        $order->title = $request->title;
        $order->instructions = $request->instructions;
        $order->essential_sources = $request->essential_sources;
        $order->suggested_sources = $request->suggested_sources;
        $order->order_no = $request->order_no;
        $order->attachment = $request->attachment;  
        // $order->status = 'Active'; 
        // $order->user_id = $request->writer;
        // $order->approved = 0;
        // $order->is_late = 0;
        // $order->is_complete =0;
        $order->update();

        OrderreportsController::store_report($request);
         return back()->with('message', 'Order updated Successfully');
    }

    public function addMoreTime(Request $request)
    {
       
        // return $request->hours;
        # code...
        if (!$request->user()->ni_admin) {
            # code...
            return back()->with('error','You are not allowed to add more hours. Kindly contact the admin for further assistance');
        }

        $order = Order::findorfail($request->order_id);
        $hours = $request->hours;
        $order->delivery_time = $order->delivery_time + $hours;
        $order->deadline = $order->created_at-> addHours($order->delivery_time); 
        $order->is_late = null;
        $order->update();

        return back()->with('message', $request->hours.' hour*s added successfully' );
    }

    public function assign_order(Request $request, Order $order)
    {
        
        $order->user_id = $request->writer;
        $order->status = $request->status;
        $order->compensation = $request->compensation;
        $order->update();
        OrderreportsController::store_report($request);
        
        // Notification message
        $user = User::find($request->writer);
        
        if ($request->status == "Active")
        {
        NotificationController::bidAcceptednotice($user, $order);
        }
        elseif ($request->status == "Active-Revision") 
        {
            NotificationController::orderRevisionnotice($user, $order);
        }
        if ($request->status == "Active")
        {
         return back()->with('message', 'Order Assigned Successfully');   
        }
        elseif($request->status == "Active-Revision")
        {
            return back()->with('message', 'Order updated Successfully'); 
        }
    }
    public function deleteit(Order $order)
    {
        $order->delete();
        return redirect('orders')->with('delete_message','Order has been Deleted') ;
    }

    public function search(Request $request)
    {

        $q= $request->q;
        //return $request->all();

        $orders = Order::where('order_no','like', '%'.$q.'%')
        ->orderBy('order_no')
        ->paginate(20);
        
        return view('pages.search', [ 
    'site_title' => PagesController::$site_title,
    'page_title' => 'You searched for '.$q,
    'page_description' =>PagesController::$page_description='All the orders found for '.$q,
    'notifications_no' =>PagesController::$notifications_no,
    'list_notifications' =>PagesController::$list_notifications,
    'number_tasks' =>PagesController::$number_tasks,
    'list_tasks' => PagesController::$list_tasks,
    'list_notifications' => PagesController::$list_notifications,
    
    
    'user_description' => PagesController::$user_description,
    'join_date_text' => PagesController::$join_date_text,
    'version_no' => PagesController::$version_no,
    
    'orders' => $orders,
    'q'=>$q]);  
    }
    

    
}
