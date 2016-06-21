<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Note;
use App\Order;
use App\User;
use Auth;
use App\Http\Controllers\NotificationController;

class NotesController extends Controller
{
    public function storeNote(Request $request)
    {
    	$order = Order::findorfail($request->order_id);
    	$assigned_writer = $order->user();

    	$note = new Note;
    	$note->order_id = $request->order_id;
    	$note->body = $request->body;
    	$note->user_id = $request->user_id;

    	if(Auth::user()->ni_admin !== '1' )
    	{
    		if (Auth::user()->id !== $order->user_id) {
    			
    		 return back()->with('Error', 'Only Admins or Assigned writers can add a message to this order');
    			// return back()->with('Error', 'ni_admin'.$assigned_writer->ni_admin.'Order user_id'.$order->user_id);
    		
    		}
    	}

    	$note->save();

    	// We should create code for sending notification to the Writer here 
    	if(Auth::user()->ni_admin)
    	{
    		$user = $order->user;
            NotificationController::orderMessageNotice($user,$order);	
    	}
        else
        {
            NotificationController::adminOrderMessageNotice($order);
        }

    	return back()->with('message','Your Message was added successfully');
    }
}
