<?php

namespace App\Http\Controllers;

use Validator;
use App\Http\Controllers\PagesController;
use App\Helpers;


use Illuminate\Http\Request;

use App\Http\Requests;

use App\User;
use Mail;


class ClientsRegistrationController extends Controller
{

    // We define our variables to use on the class

    protected $from = 'noreply@writemyclassessay.com';
    $domain = 'writemyclassessay.com';

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
     //Function to generate a strong password for our new customers.
     function generateStrongPassword($length = 9, $add_dashes = false, $available_sets = 'luds')
        {
            $sets = array();
            if(strpos($available_sets, 'l') !== false)
                $sets[] = 'abcdefghjkmnpqrstuvwxyz';
            if(strpos($available_sets, 'u') !== false)
                $sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
            if(strpos($available_sets, 'd') !== false)
                $sets[] = '23456789';
            if(strpos($available_sets, 's') !== false)
                $sets[] = '!@#$%&*?';
            $all = '';
            $password = '';
            foreach($sets as $set)
            {
                $password .= $set[array_rand(str_split($set))];
                $all .= $set;
            }
            $all = str_split($all);
            for($i = 0; $i < $length - count($sets); $i++)
                $password .= $all[array_rand($all)];
            $password = str_shuffle($password);
            if(!$add_dashes)
                return $password;
            $dash_len = floor(sqrt($length));
            $dash_str = '';
            while(strlen($password) > $dash_len)
            {
                $dash_str .= substr($password, 0, $dash_len) . '-';
                $password = substr($password, $dash_len);
            }
            $dash_str .= $password;
            return $dash_str;
        }

    public function store_client(Request $request)
    {   
        if (starts_with(Request::root(), 'https://'))
        {
            $domain = substr (Request::root(), 12); // $domain is now 'example.com'
            $from = 'support@'.$domain;
        }

    	$this->validate($request,
            ['email'=>'required|unique:users,email']); 
            
            $user = new User;
            $user->email = $request->email;
            $user->password = bcrypt(generateStrongPassword(9,false,'luds'));
            // $user->confirmation_code = $confirmation_code;
            $user->ip = $request->getClientIp();
            $user->verified = 1;
            $user->status ='1';
            $user->domain= $domain;
            
            $user->save(); 
            //Mail to client for joining
         Mail::send('emails.clientpassword',['user'=>$user, 'password' => $request->password ],function ($m) use ($user,$request)
        {
            $m->from([$from],'Top Academic Paper writing Platform');

            $m->to([$user->email])->subject('Welcome to '.$domain.'- The Complete Paper Writing Service');
        });

         //Mail to Admins for new registration

         Mail::send('emails.admin_new_user',['user'=>$user, 'confirmation_code' => $confirmation_code ],function ($m) use ($user)
        {
            $m->from('notifications@academicresearchassistants.com','Academic Research Assistants');

            $m->to(['m@writemyclassessay.com','chris@writemyclassessay.com'], 'Admin')->subject('A new Client successfully Registered on '.$domain);
        });
        	
         return response()->json('user created successfully', 200);
    }
}
