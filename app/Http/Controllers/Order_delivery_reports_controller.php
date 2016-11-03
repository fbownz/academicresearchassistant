<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Order;
use App\Order_delivery_report;
use App\User;

use Illuminate\Support\Facades\File;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage; 
use App\Http\Controllers\NotificationController;
use Carbon\Carbon ;

class Order_delivery_reports_controller extends Controller
{
     public function __construct()
    {
        $this->middleware('auth');

    }
    
    public static function store_report(Request $request)
    {
    	$current_order = Order::findorfail($request->order_id);
        $user = User::findorfail($request->user_id);
    	

    	$file = $request->file('file');
        $extension = $file->getClientOriginalExtension();
        Storage::disk('orders')->put($file->getClientOriginalName(), File::get($file));
    	// $file_url = $request->file('file')->move(storage_path(), $request->file('file')->getClientOriginalName());
        
    	
        $Order_delivery_report = new Order_delivery_report;
        $Order_delivery_report->mime = $file->getClientMimeType();
        $Order_delivery_report->original_filename = $file->getClientOriginalName();
        $Order_delivery_report->file_name = $file->getClientOriginalName();
    	$Order_delivery_report->order_id = $request->order_id;
    	$Order_delivery_report->user_id = $request->user_id;
    	$Order_delivery_report->is_complete = $request->is_complete;
    	$current_order->is_complete = $request->is_complete;

    	$Order_delivery_report->save();

    	if ($request->is_complete) {
    		$current_order->status = "Delivered";

		}
		else{
			$current_order->status = "Draft";
		}
    	
    	$current_order->update();
        //We send a message to the writer and the admin that we've received their paper
        NotificationController::orderDeliveryNotice($user, $current_order);
        NotificationController::adminOrderDeliveryNotice($user, $current_order);
    	return back()->with('message', 'Paper uploaded Successfully');
    	
    }

    public function download($file_name)
    {
        $file_uploaded =Order_delivery_report::where('file_name','=', $file_name)->firstOrFail();
        
        // I updated the disks on the file_system files so I need to check 
        // if the order the user is requesting was uploaded before or after 
        // I made the changes

        $update_date = Carbon::create(2016, 5, 11, 11,00,00);
        if ($file_uploaded->updated_at > $update_date ) 
        {
            $disk = 'orders';
        }
        else
        {
            $disk = 'local';
        }
        $file = Storage::disk($disk)->get($file_uploaded->file_name);

        return (new Response($file, 200))
            ->header('Content-Type',$file_uploaded->mime);
    }
}
