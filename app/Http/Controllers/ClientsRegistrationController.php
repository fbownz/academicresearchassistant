<?php

namespace App\Http\Controllers;

use Validator;
use App\Http\Controllers\PagesController;
use App\Helpers;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;


use Illuminate\Http\Request;

use App\Http\Requests;

use App\User;
use App\Website;
use Mail;


class ClientsRegistrationController extends Controller
{

    // We define our variables to use on the class

    protected $from = 'support@writemyclassessay.com';
    protected $domain = 'writemyclassessay.com';

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

     public function checkEMail(Request $request)
    {
    // We will checkMail and create a new user if no user is found with that mail.
    $prospect_user = User::where('email', $request->email)->get();
    $domain = "writemyclassessay.com";
    $from = "support@writemyclassessay.com";

    if ($prospect_user->count() == 0) {
                
        $domain =Website::findorfail($request->domain)->domain;
        $from = 'support@'.$domain;

       
        $this->validate($request,['email'=>'required|unique:users,email']); 
        $sp = $this->generateStrongPassword(9,false,'luds');

        $user = new User;
        $user->email = $request->email;
        $user->password = bcrypt($sp);
        // $user->confirmation_code = $confirmation_code;
        // $user->ip = $request->getClientIp($sp);
        $user->verified = 1;
        $user->status ='1';
        $user->domain=$request->domain;
        $user->prof_pic_url=""; //we are giving them a blank profpic so as to 
        $user->save(); 
        
        // We create a token for the user so as to authenticate them
        try {
            //We create a token from the user object instead
            // https://github.com/tymondesigns/jwt-auth/wiki/Creating-Tokens#creating-a-token-based-on-a-user-object

            $token = JWTAuth::fromUser($user);   
        } catch (JWTException $e) {
            // Something went wrong on the JWTAuth library while trying to encode so error 500 returned.
            return response()->json(['error' => 'Could not create Token contact Supprt: '.$e], 500);
        }


        //Mail to client for joining

         Mail::send('emails.clientPasswd',['user'=>$user, 'password' => $sp, 'domain'=> $domain ],function ($m) use ($user,$sp,$domain,$from)
        {
            $m->from($from,'Academic Paper writing Platform');

            $m->to([$user->email])->subject('Welcome to '.$domain.'- Check your login details and finish up your order for free!');
        });

         //Mail to Admins for new registration

         Mail::send('emails.admin_new_Client',['user'=>$user, 'domain'=>$domain],function ($m) use ($user,$from,$domain)
        {
            $m->from($from,'Academic Paper writing Platform');

            $m->to(['m@writemyclassessay.com','chris@writemyclassessay.com','academicresearchassistants@gmail.com'], 'Admin')->subject('A new Client successfully Registered on '.$domain);
        });

         //We respond with the token and success status  
         return response()->json(['token' => $token], 200);
         // return response()->json(compact($token), 209);

    } else  {
        return response()->json('A registered user', 201);    }
    
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

    // public function store_client($newUser_request,$request)
    // {   
        
    // }
        public function resetClientPass(Request $request)
        {
           
            $domain =Website::findorfail($request->domain)->domain;
            $from = 'support@'.$domain;

           
            // $this->validate($request,['email'=>'required|unique:users,email']); 
            $sp = $this->generateStrongPassword(9,false,'luds');

            $user = User::where('email',$request->email)->first();
            if (!$user) {
                return response()->json('No user found', 401);    
            }
            
            $user->password = bcrypt($sp); 
            $user->update(); 
        

            //We send an email to the user and myself to know when a user has reset their password
             Mail::send('emails.clientResetPasswd',['user'=>$user, 'password' => $sp, 'domain'=> $domain ],function ($m) use ($user,$sp,$domain,$from)
            {
                $m->from($from,'Academic Paper writing Platform');

                $m->to([$user->email])->subject('New login details on '.$domain);
            });


             Mail::send('emails.admin_Client_ResetPasswd',['user'=>$user, 'domain'=>$domain],function ($m) use ($user,$from,$domain)
            {
                $m->from($from,'Academic Paper writing Platform');

                $m->to(['m@writemyclassessay.com'], 'Admin')->subject('A reset password request on '.$domain);
            });
            return response()->json('Reset password Successfully check your email', 200);
        }
}
