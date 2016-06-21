<?php

namespace App\Http\Controllers;

use Validator;
use App\Http\Controllers\PagesController;
use App\Helpers;


use Illuminate\Http\Request;

use App\Http\Requests;

use App\User;
use Mail;


class RegistrationController extends Controller
{

    // We define our variables to use on the class

    protected $from = 'noreply@academicresearchassistants.com';

    protected $to;

    /**
     * The view for the email.
     *
     * @var string
     */
    protected $view;

    /**
     * The data associated with the view for the email.
     *
     * @var array
     */
    protected $data = [];


 	public function __construct()
 	   {
     	   $this->middleware('guest');
   	 }
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
            'last_name'=>'required|min:4',
            'email'=>'required|unique:users,email',
            'password'=>'required|confirmed',
            // 'password_confirmation'=>'required|confirmed',
            'checkbox' =>'required'
            ]); 
        $confirmation_code = str_random(30);
    	$user = new User;
    	$user->email = $request->email;
    	$user->first_name = $request->first_name;
    	$user->last_name = $request->last_name;
    	$user->password = bcrypt($request->password);
        $user->confirmation_code = $confirmation_code;

    	$user->ip = $request->getClientIp();

    	
        $user->save();

         Mail::send('emails.confirm',['user'=>$user, 'confirmation_code' => $confirmation_code ],function ($m) use ($user)
        {
            $m->from('notifications@academicresearchassistants.com','Academic Research Assistants');

            $m->to([$user->email], $user->first_name)->subject('Verify your email address: Academic Research Assistants');
        });
        	

        	 Helpers::flash($user->first_name.' You have Successfuly registered an account Kindly check your email '.$user->email.' for an activation link, Check your Spam folder too if you do not find any email');

            	return redirect()->back();

    }
    
    
	/**
	*We need to confirm the user's email 
	*/
	public function confirmEmail($confirmation_code){
        if( ! $confirmation_code)
        {
            Helpers::flash('We have not found any confirmation code in your request are you sure you registered? Try to Login instead');
            return redirect('login');
        }
        $user = User::where('confirmation_code',$confirmation_code)->first();

        if ( ! $user)
        {
            // throw new InvalidConfirmationCodeException;
            Helpers::flash('No User found! Have you already verified your account? Try to login instead');
            return redirect('login');
        }
        $user->verified = 1;
        $user->status ='1';
        $user->confirmation_code = null;
        $user->save();


         Mail::send('emails.activated_user',['user'=>$user],function ($m) use ($user)
        {
            $m->from('notifications@academicresearchassistants.com','Academic Research Assistants');

            $m->to([$user->email], $user->first_name)->subject('Welcome To Academic Research Assistants');
        });

         Mail::send('emails.admin_new_user',['user'=>$user, 'confirmation_code' => $confirmation_code ],function ($m) use ($user)
        {
            $m->from('notifications@academicresearchassistants.com','Academic Research Assistants');

            $m->to(['m@writemyclassessay.com','chris@writemyclassessay.com'], 'Admin')->subject('A new User has successfully Registered on ARA');
        });

        Helpers::flash($user->first_name.' You have successfully verified your account. Login to complete your profile and start Bidding :)');

	return redirect('login');


	}
}
