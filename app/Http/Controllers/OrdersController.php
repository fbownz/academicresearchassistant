<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\order;
use App\User;
use App\Bid;
use Carbon\Carbon;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\Order_delivery_reports;
use App\Http\Controllers\OrderreportsController;

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
    foreach ($orders as $order) 
    {
        if ($order->is_late == null) {
            if ( $order->deadline < Carbon::now() && $order->status != "Completed" && $order->status != "Delivered"   ) {
                $order->is_late = 1;

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

            $orders =Order::orderBy($sortBy,'desc')->paginate(20);

          foreach ($orders as $order) {
                if ($order->is_late == null) {
                    if ( $order->deadline < Carbon::now() && $order->status != "Completed" && $order->status != "Delivered" ) {
                        $order->is_late = 1;
                        
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
         echo 'Are you Sure contact @fbownz n let him know what you were doing before you got this message';

     }

    public function show(Order $order){
        $users_to_be_assigned = User::whereRaw('status = 1 and ni_admin = 0')->get();
        $bids_submitted = Bid::where('order_id','=',$order->id)->orderBy('created_at','desc')->get();

         return view('pages.order', [ 
        'site_title' => PagesController::$site_title,
        'page_title' => $order->order_no,
        'page_description' =>PagesController::$page_description='Order details',
        'notifications_no' =>PagesController::$notifications_no,
        'number_tasks' =>PagesController::$number_tasks,
        'list_tasks' => PagesController::$list_tasks,
        'list_notifications' => PagesController::$list_notifications,
        
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
    'page_description' =>PagesController::$page_description='',
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
            ['order_no'=>'required',
            'type_of_product'=>'required',
            ]); 
        $order = new Order;
        $order->type_of_product = $request->type_of_product;
        $order->subject = $request->subject;
        $order->word_length = $request->word_length;
        $order->spacing = $request->spacing;
        $order->academic_level = $request->academic_level;
        $hours= $request->delivery_time;
        // $new_deadline = date("Y-m-d H:i:s",strtotime(sprintf("+%d hours", $hours)));
        $today= Carbon::now();


        $order->deadline = $today->addHours($hours);
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
        $order->status = $request->status;
        $order->user_id = $request->writer;


        $order->save();
        $order_report_order_id = $order->id ;
        return redirect('orders')->with('message','Order has been Added Successfully');
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
        $users = User::whereRaw('status = 1 and ni_admin = 0')->get();
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
        $order->status = $request->status; 
        $order->user_id = $request->writer;
             
        $order->update();
        OrderreportsController::store_report($request);
         return back()->with('message', 'Order updated Successfully');
    }
    public function assign_order(Request $request, Order $order)
    {
        
        $order->user_id = $request->writer;
        $order->status = $request->status;
        $order->update();
        OrderreportsController::store_report($request);
         return back()->with('message', 'Order Status updated Successfully');
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
        
        return view('pages.dashboard', [ 
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
