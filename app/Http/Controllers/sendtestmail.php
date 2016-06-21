<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Mail;
use App\User;




class sendtestmail extends Controller
{
	 // We are testing out our mail settings
	 public static function sendtestmail(Request $request, $id)   
	 {
	 	$user = User::findOrFail($id);
	 	$testVar = "Test stuff that we send out to the Guy";

	 	Mail::queue('emails.test',['user'=>$user, 'testVar'=>$testVar],function ($m) use ($user)
	 	{
	 		$m->from('test@academicresearchassistants.com','Academic Research Assistant');

	 		$m->to($user->email, $user->first_name)->subject('Test Mail from Academic guys');
	 	});

	 	return redirect('/login') ;
	 }
}
