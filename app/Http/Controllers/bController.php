<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\B_detail;

class bController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request,
            ['b_name'=>'required',
            'b_b_name'=>'required',
            'a_name'=>'required',
            'a_number'=>'required',
            ]); 

        $user = $request->user();

    	$b_detail = new B_detail;
    	$b_detail->b_name = $request->b_name;
    	$b_detail->b_b_name = $request->b_b_name;
    	$b_detail->a_name = $request->a_name;
    	$b_detail->a_number = $request->a_number;
    	$b_detail->user_id = $user->id;

    	$b_detail->save();

        return back()->with('message', 'Bank details added successfully');

    }
    public function delete(Request $request, $id)
    {
        $user = $request->user();
        $b_detail = B_detail::findorFail($id);
        if($b_detail->user->id !== $user->id)
        {
            return back()->with('error1', 'Only authorised users can delete their bank accounts');
        }

        $b_detail->delete();

        return back()->with('message', 'Bank detail deleted successfully');
    }
    public function update(Request $request)
    {
        $user = $request->user();
        $b_detail = $user->b_details->first();

        $b_detail->b_name = $request->b_name;
        $b_detail->b_b_name = $request->b_b_name;
        $b_detail->a_name = $request->a_name;
        $b_detail->a_number = $request->a_number;

        $b_detail->update();

        return back()->with('message', 'Bank details updatede successfully');
    }
}
