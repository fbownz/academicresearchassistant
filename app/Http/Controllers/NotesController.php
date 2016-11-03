<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Note;
use App\Order;
use App\User;
use Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage; 
use App\Http\Controllers\NotificationController;
use Carbon\Carbon;

// We use this class to store order messages from writers to admins and viceversa
class NotesController extends Controller
{
    

    public function storeNote(Request $request)
    {
        
        $order = Order::findorfail($request->order_id);

        if(!Auth::user()->ni_admin)
        {
            if (Auth::user()->id !== $order->user_id) {
                
             return back()->with('Error', 'Only Admins and Assigned writers can add a message to this order');
                
            }
        }

    	$note = new Note;
    	$note->order_id = $request->order_id;
    	$note->body = $request->body;
    	$note->user_id = $request->user_id;

        //We check if the request had a file field populated 
        //After that we populate the two fields below with the mime type of the file uploaded and the filename of the file uploaded.
        if ($request->file('msg_file')) {

            $attachment = $request->file('msg_file');
            // $extension = $attachment->getClientOriginalExtension();
            $note->original_attachment_name = $attachment->getClientOriginalName();
            $note->attachment_name ='noteMsg_'.$attachment->getClientOriginalName();
            $note->attachment_mime =$attachment->getClientMimeType();

            Storage::disk('orders')->put($note->attachment_name, File::get($attachment));
        }

        $note->save();
    	

    	// We should create code for sending notifications to the Writer here 
        $user_id = $order->user_id;
    	if(Auth::user()->ni_admin && $user_id > 0 )
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

    public function downloadnoteAttachment($original_attachment_name)
    {
        // $note = Note::findorfail($note_id);
        $note = Note::where('original_attachment_name', $original_attachment_name)->firstorfail();

        $file = Storage::disk('orders')->get($note->attachment_name);

        if($file){
            return (new Response($file, 200))
            ->header('Content-Type',$note->attachment_mime);
        }
        else
        {
            return back()->with('error',"The file requested can't be found, Kindly contact Suport for further assistance");
        }


    }
}
