<?php

namespace App\Http\Controllers;

use Validator;
use App\Http\Controllers\PagesController;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\User;

class RegistrationController extends Controller
{
        public function register()
    {
        return view('auth.register',[
            'site_title'=> PagesController::$site_title,
            'page_title'=> PagesController::$page_title='Register A new Writers Account']);
    }

    public function store_user(Request $request)
    {
    	$this->validate($request,
            ['first_name'=>'required|max:255|min:4|',
            'last_name'=>'required',
            'email'=>'required|unique:users,email',
            'password'=>'required|confirmed',
            // 'password_confirmation'=>'required|confirmed',
            'checkbox' =>'required'
            ]); 
    	$user = new User;
    	$user->email = $request->email;
    	$user->first_name = $request->first_name;
    	$user->last_name = $request->last_name;
    	$user->password = $request->password;

    	// $user->ip = $request->getClientIp();

    	$user->save();

    	return back()->with('message','User Successfully added');

    }
}
