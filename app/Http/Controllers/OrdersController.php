<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Order;
use App\User;
use App\Bid;
use App\Earning;
use App\Message;
use App\Notification;
use Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\Order_delivery_reports;
use App\Http\Controllers\OrderreportsController;
use App\Http\Controllers\NotificationController;

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

    //Determine if an order is late and mark as late by updating the late_field to 1
    // We also check if the client's delivery time is due and send a message to Admins
    // On Fri 27th May I removed "&& $order->status != "Active-Revision"" so as to update the time on active-revision orders
    foreach ($orders as $order) 
    {
        if ($order->status != "Completed" && $order->status != "Delivered"  && $order->status != "Available" && $order->approved != 1) {

            if (!$order->notifications()->where('type','order_past_client_deadline')->count() ) {
                
                // We create a notificiation to send emails and sms to Admins
                // When the client's deadline is due

                if ($order->client_deadline < Carbon::now()) {
                    NotificationController::clientdeliveryTimeNotice($order);
                }
                
                

            }

            if ( $order->deadline < Carbon::now() && $order->is_late == null) {
                
                $order->is_late = 1;


                $order->update();

                if ($order->user()->count()) 
                {
                 
                NotificationController::lateOrdersNotice($order->user,$order);   
                }
                
            }
            elseif ($order->deadline > Carbon::now() && $order->is_late != null) {
                $order->is_late = null;
                $order->update();
            }
        }
        
    }
    if(Auth::user()->ni_admin)
        {
            $ap_orders = Earning::orderBy('created_at', 'desc')->paginate(50) ;
            $no_approved= count(Earning::where('created_at', '>=', Carbon::now()->startOfMonth())->get());
            $re_orders = Order::where('status','Active-Revision')->orderBy('created_at', 'desc')->paginate(50);
            $no_revision = count(Order::where('status','Active-Revision')->get());
            $av_orders = Order::where('status','Available')->orderBy('created_at', 'desc')->paginate(50);
            $no_available = count(Order::where('status','Available')->get());
            $act_orders = Order::where('status','Active')->orderBy('created_at', 'desc')->paginate(50) ;
            $no_active = count(Order::where('status','Active')->get());

            // I have removed the need to show delivered orders for the beginning of the month
            // ->where('created_at', '>=', Carbon::now()->startOfMonth())
            $del_orders = Order::where('approved', null)->where('status', 'Delivered')->orderBy('updated_at', 'desc')->paginate(50) ;
            
            // I deleted the request for beginning of the month
            $no_delivered = count(Order::where('approved', null)->where('status', 'Delivered')->get());

            
            $lt_orders = Order::where('user_id','>', 1)->where('is_late',1)->orderBy('created_at', 'desc')->paginate(50);
            $no_late = count(Order::where('created_at', '>=', Carbon::now()->startOfMonth())->where('user_id','>', 1)->where('is_late',1)->get());
            $act_draft_orders ="";

            $prof_comp_array = 0;
            $list_messages = Message::where('for_admin', 1)->where('unread', 1)->orderBy('created_at', 'desc')->get();
            $messages_no = $list_messages->count();

            $list_bid_accepted = Notification::where('status',0)->where('type',"admin_order_bidPlaced")->orderBy('created_at', 'desc')->get();
            $list_order_message = Notification::where('status',0)->where('type',"admin_order_message")->orderBy('created_at', 'desc')->get();

            $notifications_no = $list_bid_accepted->count();
            // I used $list_bid_accepted the same as other users so as not to have a difficult time to display them on the layout

            $order_msg_no = $list_order_message->count();

            $list_tasks = Notification::where('status',0)->where('type',"admin_order_late")->orderBy('created_at', 'desc')->get();
            $number_tasks =$list_tasks->count() ;
        }
    else
        {
            $ap_orders= Auth::user()->earnings()->orderBy('created_at', 'desc')->paginate(50);
            $no_approved = count(Auth::user()->earnings()->where('created_at', '>=', Carbon::now()->startOfMonth())->get());
            $re_orders = Auth::user()->orders()->where('status','Active-Revision')->orderBy('created_at', 'desc')->paginate(50);
            $no_revision = count(Auth::user()->orders()->where('status','Active-Revision')->get());
            $av_orders = Order::where('status','Available')->orderBy('created_at', 'desc')->paginate(50);
            $no_available = count(Order::where('status','Available')->get());
            $act_orders = Auth::user()->orders()->where('status','Active')->get();
            //Draft orders
            $act_draft_orders = Auth::user()->orders()->where('status','Draft')->get();

            // I merged the active orders with the draft on the writers side in order to show draft and Active orders too
            $act_orders = $act_orders->toBase()->merge($act_draft_orders)->sortByDesc('created_at');
            $act_orders = $act_orders->values()->all();

            $no_active = count(Auth::user()->orders()->where('status','Active')->get());
            $del_orders = Auth::user()->orders()->where('status','Delivered')->orderBy('created_at', 'desc')->paginate(50);
            $no_delivered = count(Auth::user()->orders()->where('status','Delivered')->get());
            $lt_orders = Auth::user()->orders()->where('is_late','1')->orderBy('created_at', 'desc')->paginate(50);
            $no_late = count(Auth::user()->orders()->where('is_late','1')->get());

            $prof_comp_array = NotificationController::profileComplete(Auth::user());

              $list_messages = Auth::user()->messages->where('unread',1)->sortByDesc('created_at');
              $messages_no = $list_messages->count();

              $list_bid_accepted = Auth::user()->notifications()->where('status',0)->where('type','order_bid_accepted')->orderBy('created_at', 'desc')->get();
              $list_order_message = Auth::user()->notifications()->where('status',0)->where('type','order_message')->orderBy('created_at', 'desc')->get();

              // $list_notifications = $list_order_message->toBase()->merge($list_bid_accepted);
               $notifications_no = $list_bid_accepted->count();
               $order_msg_no = $list_order_message->count();
              
              // $list_notifications = $list_notifications->sortByDesc('created_at');



              $list_order_late = Auth::user()->notifications()->where('status',0)->where('type','order_late')->orderBy('created_at', 'desc')->get();
              $list_order_revisions = Auth::user()->notifications()->where('status',0)->where('type','order_revision')->orderBy('created_at', 'desc')->get();

              $list_tasks = $list_order_revisions->toBase()->merge($list_order_late);
              $number_tasks =$list_tasks->count() ;
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
        'orders' => $orders,
        'ap_orders'=>$ap_orders,
        'no_approved'=>$no_approved,
        're_orders'=>$re_orders,
        'no_revision'=>$no_revision,
        'av_orders'=>$av_orders,
        'no_available'=>$no_available,
        'act_orders'=>$act_orders,
        'act_draft_orders'=>$act_draft_orders,
        'act_orders'=>$act_orders,
        'no_active'=>$no_active,
        'del_orders'=>$del_orders,
        'no_delivered'=>$no_delivered,
        'lt_orders'=>$lt_orders,
        'no_late'=>$no_late,
        'messages_no' => $messages_no,
        'list_messages' => $list_messages,
        'order_msg_no' => $order_msg_no,
        'list_order_message' => $list_order_message,
        'notifications_no' => $notifications_no,
        'list_bid_accepted' => $list_bid_accepted,
        'list_tasks' => $list_tasks,
        'number_tasks' => $number_tasks,
        'prof_comp_array' => $prof_comp_array,
        ]);
 }
     // public function sort(Request $request)
     // {
     //     $orderBy = [
     //     'order_no','status','created_at','total','deadline'];

     //     if (in_array($request->sort, $orderBy)) {
     //        $sortBy = $request->sort;

     //        $orders =Order::orderBy($sortBy,'desc')->paginate(50);

          

     //         return view('pages.orders-table',[ 
     //        'site_title' => PagesController::$site_title,
     //        'page_title' => PagesController::$page_title='Orders',
     //        'page_description' =>PagesController::$page_title='A snapshot of all the recent orders',
     //        'notifications_no' =>PagesController::$notifications_no,
     //        'list_notifications' =>PagesController::$list_notifications,
     //        'number_tasks' =>PagesController::$number_tasks,
     //        'list_tasks' => PagesController::$list_tasks,
            
            
     //        'user_description' => PagesController::$user_description,
     //        'join_date_text' => PagesController::$join_date_text,
     //        'version_no' => PagesController::$version_no,
            
     //        'orders' => $orders]);
     //    }  
     //     return back()->with('error','Are you Sure contact @fbownz n let him know what you were doing before you got this message');

     // }

    public function show(Request $request, Order $order){

        // I use this so as not to show Orders to people not asigned
        if (!$request->user()->ni_admin && $order->status !== 'Available' && $order->status !=="Not-Available" ) {
            if ($order->user->id !== $request->user()->id) {
                Return redirect('orders')->with('warning','Hey '.$request->user()->first_name.', You do not have permision to view Order '.$order->order_no.' It has already been assigned.');
            }
        }



        // We only assign orders to active users who have ever placed more than one bid
        $users_to_be_assigned = User::has('bids')->where('status', "1")->where('verified', 1)->orderBy('first_name','asc')->get();

        $bids_submitted = Bid::where('order_id','=',$order->id)->orderBy('created_at','asc')->get();

         if (Auth::user()->ni_admin )
        {
            $prof_comp_array = 0;
            $list_messages = Message::where('for_admin', 1)->where('unread', 1)->orderBy('created_at', 'desc')->get();
            $messages_no = $list_messages->count();

            $list_bid_accepted = Notification::where('status',0)->where('type',"admin_order_bidPlaced")->orderBy('created_at', 'desc')->get();
            $list_order_message = Notification::where('status',0)->where('type',"admin_order_message")->orderBy('created_at', 'desc')->get();


            // $list_notifications = $list_order_message->toBase()->merge($list_bid_accepted);
            $notifications_no = $list_bid_accepted->count();
            // I used $list_bid_accepted the same as other users so as not to have a difficult time to display them on the layout

            $order_msg_no = $list_order_message->count();

            // $list_notifications = $list_notifications->sortByDesc('created_at');

            $list_tasks = Notification::where('status',0)->where('type',"admin_order_late")->orderBy('created_at', 'desc')->get();
            $number_tasks =$list_tasks->count() ;
         
        }
        else
        {
             
          $prof_comp_array = NotificationController::profileComplete(Auth::user());

          $list_messages = Auth::user()->messages->where('unread',1)->sortByDesc('created_at');
          $messages_no = $list_messages->count();

          $list_bid_accepted = Auth::user()->notifications()->where('status',0)->where('type','order_bid_accepted')->orderBy('created_at', 'desc')->get();
          $list_order_message = Auth::user()->notifications()->where('status',0)->where('type','order_message')->orderBy('created_at', 'desc')->get();

          // $list_notifications = $list_order_message->toBase()->merge($list_bid_accepted);
           $notifications_no = $list_bid_accepted->count();
           $order_msg_no = $list_order_message->count();
          
          // $list_notifications = $list_notifications->sortByDesc('created_at');



          $list_order_late = Auth::user()->notifications()->where('status',0)->where('type','order_late')->orderBy('created_at', 'desc')->get();
          $list_order_revisions = Auth::user()->notifications()->where('status',0)->where('type','order_revision')->orderBy('created_at', 'desc')->get();

          $list_tasks = $list_order_revisions->toBase()->merge($list_order_late);
          $number_tasks =$list_tasks->count() ;

        }

         return view('pages.order', [ 
        'site_title' => PagesController::$site_title,
        'page_title' => $order->order_no,
        'page_description' =>PagesController::$page_description='Order details',
        
        
        'user_description' => PagesController::$user_description,
        'join_date_text' => PagesController::$join_date_text,
        'version_no' => PagesController::$version_no,
        'order' => $order,
        'writers' => $users_to_be_assigned,
        'bids' => $bids_submitted, 
        'messages_no' => $messages_no,
        'list_messages' => $list_messages,
        'order_msg_no' => $order_msg_no,
        'list_order_message' => $list_order_message,
        'notifications_no' => $notifications_no,
        'list_bid_accepted' => $list_bid_accepted,
        'list_tasks' => $list_tasks,
        'number_tasks' => $number_tasks,
        'prof_comp_array' => $prof_comp_array,
        ]);

    // return $card;
        }

    public function findWork()
    {
        $or = Order::where('status','available')->get();

         if (Auth::user()->ni_admin )
        {
            $prof_comp_array = 0;
            $list_messages = Message::where('for_admin', 1)->where('unread', 1)->orderBy('created_at', 'desc')->get();
            $messages_no = $list_messages->count();

            $list_bid_accepted = Notification::where('status',0)->where('type',"admin_order_bidPlaced")->orderBy('created_at', 'desc')->get();
            $list_order_message = Notification::where('status',0)->where('type',"admin_order_message")->orderBy('created_at', 'desc')->get();


            // $list_notifications = $list_order_message->toBase()->merge($list_bid_accepted);
            $notifications_no = $list_bid_accepted->count();
            // I used $list_bid_accepted the same as other users so as not to have a difficult time to display them on the layout

            $order_msg_no = $list_order_message->count();

            // $list_notifications = $list_notifications->sortByDesc('created_at');

            $list_tasks = Notification::where('status',0)->where('type',"admin_order_late")->orderBy('created_at', 'desc')->get();
            $number_tasks =$list_tasks->count() ;
         
        }
        else
        {
             
          $prof_comp_array = NotificationController::profileComplete(Auth::user());

          $list_messages = Auth::user()->messages->where('unread',1)->sortByDesc('created_at');
          $messages_no = $list_messages->count();

          $list_bid_accepted = Auth::user()->notifications()->where('status',0)->where('type','order_bid_accepted')->orderBy('created_at', 'desc')->get();
          $list_order_message = Auth::user()->notifications()->where('status',0)->where('type','order_message')->orderBy('created_at', 'desc')->get();

          // $list_notifications = $list_order_message->toBase()->merge($list_bid_accepted);
           $notifications_no = $list_bid_accepted->count();
           $order_msg_no = $list_order_message->count();
          
          // $list_notifications = $list_notifications->sortByDesc('created_at');



          $list_order_late = Auth::user()->notifications()->where('status',0)->where('type','order_late')->orderBy('created_at', 'desc')->get();
          $list_order_revisions = Auth::user()->notifications()->where('status',0)->where('type','order_revision')->orderBy('created_at', 'desc')->get();

          $list_tasks = $list_order_revisions->toBase()->merge($list_order_late);
          $number_tasks =$list_tasks->count() ;

        }


        return view('pages.available_orders',[ 
        'site_title' => PagesController::$site_title,
        'page_title' => PagesController::$page_title='Find Work',
        'page_description' =>PagesController::$page_title='Browse orders on ARA',
        'notifications_no' =>PagesController::$notifications_no,
        'list_notifications' =>PagesController::$list_notifications,
        'number_tasks' =>PagesController::$number_tasks,
        'list_tasks' => PagesController::$list_tasks,
        'user_description' => PagesController::$user_description,
        'join_date_text' => PagesController::$join_date_text,
        'version_no' => PagesController::$version_no,
        'or' => $or, 
        'messages_no' => $messages_no,
        'list_messages' => $list_messages,
        'order_msg_no' => $order_msg_no,
        'list_order_message' => $list_order_message,
        'notifications_no' => $notifications_no,
        'list_bid_accepted' => $list_bid_accepted,
        'list_tasks' => $list_tasks,
        'number_tasks' => $number_tasks,
        'prof_comp_array' => $prof_comp_array,
        ]);
    }
    public function new_order()
    { 
        $users = User::whereRaw('status = 1 and ni_admin = 0 and verified = 1')->get();
        $countries = array("Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegowina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, the Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia (Hrvatska)", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "France Metropolitan", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard and Mc Donald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Lao, People's Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovakia (Slovak Republic)", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "Spain", "Sri Lanka", "St. Helena", "St. Pierre and Miquelon", "Sudan", "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (U.S.)", "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Yugoslavia", "Zambia", "Zimbabwe");
          $subect_options=[null,'Accounting','Advertising/Public Relations','Alternative Dispute Resolution (ADR)/Mediation','Animal/Plant Biology','Anthropology','Archaeology','Architecture','Art','Biology','Business','Chemistry','Children & Young People','Civil Litigation Law','Commercial Law','Commercial Property Law','Communications','Company/Business/Partnership Law','Comparative/Conflict of Laws','Competition Law','Computer Science','Constitutional/Administrative Law','Construction','Construction Law','Contract Law','Counselling','Criminal Justice System/Process (Law)','Criminal Law','Criminal Litigation (Law)','Criminology','Cultural Studies','Dentistry','Design','Drama','Economics','Economics (Social Sciences)','Education','Employment','Employment Law','Engineering','English Language','English Legal System (Law)','English Literature','Environment','Environmental Sciences','Equity & Trusts Law','Estate Management','European (EU) Law','European Studies','Family Law','Fashion','Film Studies','Finance','Finance Law','Food and Nutrition','Forensic Science','French','General Law','Geography','Geology','German','Health','Health & Social Care','Health and Safety','Health Psychology','History','Housing','Human Resource Management','Human Rights','Human Rights Law','Immigration/Refugee Law','Information - Media & Technology Law','Information Systems','Information Technology','Intellectual Property Law','International Commercial Law','International Criminal Law','International Law','International Political Economy','International Relations','International Studies','Jurisprudence (Law)','Land/property Law','Landlord & Tenant/Housing Law','Law of Evidence','Legal Professional Ethics (Law)','Linguistics','Management','Maritime Law','Marketing','Maths','Media','Medical Law','Medical Technology','Medicine','Mental Health','Mental Health Law','Methodology','Music','Nursing','Occupational Therapy','Oil & Gas Law','Other','Paramedic Studies','Pharmacology','Philosophy','Photography','Physical Education','Physics','Physiotherapy','Planning/Environmental Law','Politics','Professional Conduct Law','Psychology','Psychotherapy','Public Administration','Public Law','Quantity Surveying','Real Estate','Research report for management','Security & Risk Management','Social Policy','Social Work','Social Work Law','Sociology','Spanish','Sports Law','Sports Psychology','Sports Science','SPSS','Statitstics','StatisticsTax Law','Teacher Training / PGCETESOL','Theatre Studies','Theology & Religion','Tort Law','Tourism & Hospitality','Town & Country Planning','Translation'];
          $type_of_product_options=[null,'Essay','Term Paper','Research Paper','Coursework','Book Report','Book Review','Movie Review','Research Summary','Dissertation','Thesis','Thesis/Dissertation Proposal','Research Proposal','Dissertation Chapter - Abstract','Dissertation Chapter - Introduction Chapter','Dissertation Chapter - Literature Review','Dissertation Chapter - Methodology','Chapter - Results','Dissertation Chapter - Discussion','Dissertation Services - Editing','Dissertation Services - Proofreading','Formatting','Admission Services - Admission Essay','Admission Services - Scholarship Essay','Admission Services - Personal Statement','Admission Services - Editing','Editing','Proofreading','Case Study','Lab Report','Speech/Presentation','Math/Physics/Economics/Statistics Problems','Computer Science Project','Article','Article Critique|','Annotated Bibliography','Reaction Paper','Memorandum','Real Estate License exam','Multiple Choice Questions (Non-time-framed)','Multiple Choice Questions (Time-framed)','Statistics Project','PowerPoint Presentation','Mind/Concept mapping','Multimedia Project','Simulation report','Problem Solving'];
          $word_length_options=[null,
                '1 page-275 words', 
                '2 page-550 words', 
                '3 page-825 words', 
                '4 page-1100 words', 
                '5 page-1375 words', 
                '6 page-1650 words', 
                '7 page-1925 words', 
                '8 page-2200 words', 
                '9 page-2475 words', 
                '10 page-2750 words', 
                '11 page-3025 words', 
                '12 page-3300 words', 
                '13 page-3575 words', 
                '14 page-3850 words', 
                '15 page-4125 words', 
                '16 page-4400 words', 
                '17 page-4675 words', 
                '18 page-4950 words', 
                '19 page-5225 words', 
                '20 page-5500 words', 
                '21 page-5775 words', 
                '22 page-6050 words', 
                '23 page-6325 words', 
                '24 page-6600 words', 
                '25 page-6875 words', 
                '26 page-7150 words', 
                '27 page-7425 words', 
                '28 page-7700 words', 
                '29 page-7975 words', 
                '30 page-8250 words', 
                '31 page-8525 words', 
                '32 page-8800 words', 
                '33 page-9075 words', 
                '34 page-9350 words', 
                '35 page-9625 words', 
                '36 page-9900 words', 
                '37 page-10175 words', 
                '38 page-10450 words', 
                '39 page-10725 words', 
                '40 page-11000 words', 
                '41 page-11275 words', 
                '42 page-11550 words', 
                '43 page-11825 words', 
                '44 page-12100 words', 
                '45 page-12375 words', 
                '46 page-12650 words', 
                '47 page-12925 words', 
                '48 page-13200 words', 
                '49 page-13475 words', 
                '50 page-13750 words', 
                '51 page-14025 words', 
                '52 page-14300 words', 
                '53 page-14575 words', 
                '54 page-14850 words', 
                '55 page-15125 words', 
                '56 page-15400 words', 
                '57 page-15675 words', 
                '58 page-15950 words', 
                '59 page-16225 words', 
                '60 page-16500 words', 
                '61 page-16775 words', 
                '62 page-17050 words', 
                '63 page-17325 words', 
                '64 page-17600 words', 
                '65 page-17875 words', 
                '66 page-18150 words', 
                '67 page-18425 words', 
                '68 page-18700 words', 
                '69 page-18975 words', 
                '70 page-19250 words', 
                '71 page-19525 words', 
                '72 page-19800 words', 
                '73 page-20075 words', 
                '74 page-20350 words', 
                '75 page-20625 words', 
                '76 page-20900 words', 
                '77 page-21175 words', 
                '78 page-21450 words', 
                '79 page-21725 words', 
                '80 page-22000 words', 
                '81 page-22275 words', 
                '82 page-22550 words', 
                '83 page-22825 words', 
                '84 page-23100 words', 
                '85 page-23375 words', 
                '86 page-23650 words', 
                '87 page-23925 words', 
                '88 page-24200 words', 
                '89 page-24475 words', 
                '90 page-24750 words', 
                '91 page-25025 words', 
                '92 page-25300 words', 
                '93 page-25575 words', 
                '94 page-25850 words', 
                '95 page-26125 words', 
                '96 page-26400 words', 
                '97 page-26675 words', 
                '98 page-26950 words', 
                '99 page-27225 words', 
                '100 page-27500 words', 
                '101 page-27775 words', 
                '102 page-28050 words', 
                '103 page-28325 words', 
                '104 page-28600 words', 
                '105 page-28875 words', 
                '106 page-29150 words', 
                '107 page-29425 words', 
                '108 page-29700 words', 
                '109 page-29975 words', 
                '110 page-30250 words', 
                '111 page-30525 words', 
                '112 page-30800 words', 
                '113 page-31075 words', 
                '114 page-31350 words', 
                '115 page-31625 words', 
                '116 page-31900 words', 
                '117 page-32175 words', 
                '118 page-32450 words', 
                '119 page-32725 words', 
                '120 page-33000 words', 
                '121 page-33275 words', 
                '122 page-33550 words', 
                '123 page-33825 words', 
                '124 page-34100 words', 
                '125 page-34375 words', 
                '126 page-34650 words', 
                '127 page-34925 words', 
                '128 page-35200 words', 
                '129 page-35475 words', 
                '130 page-35750 words', 
                '131 page-36025 words', 
                '132 page-36300 words', 
                '133 page-36575 words', 
                '134 page-36850 words', 
                '135 page-37125 words', 
                '136 page-37400 words', 
                '137 page-37675 words', 
                '138 page-37950 words', 
                '139 page-38225 words', 
                '140 page-38500 words', 
                '141 page-38775 words', 
                '142 page-39050 words', 
                '143 page-39325 words', 
                '144 page-39600 words', 
                '145 page-39875 words', 
                '146 page-40150 words', 
                '147 page-40425 words', 
                '148 page-40700 words', 
                '149 page-40975 words', 
                '150 page-41250 words', 
                '151 page-41525 words', 
                '152 page-41800 words', 
                '153 page-42075 words', 
                '154 page-42350 words', 
                '155 page-42625 words', 
                '156 page-42900 words', 
                '157 page-43175 words', 
                '158 page-43450 words', 
                '159 page-43725 words', 
                '160 page-44000 words', 
                '161 page-44275 words', 
                '162 page-44550 words', 
                '163 page-44825 words', 
                '164 page-45100 words', 
                '165 page-45375 words', 
                '166 page-45650 words', 
                '167 page-45925 words', 
                '168 page-46200 words', 
                '169 page-46475 words', 
                '170 page-46750 words', 
                '171 page-47025 words', 
                '172 page-47300 words', 
                '173 page-47575 words', 
                '174 page-47850 words', 
                '175 page-48125 words', 
                '176 page-48400 words', 
                '177 page-48675 words', 
                '178 page-48950 words', 
                '179 page-49225 words', 
                '180 page-49500 words', 
                '181 page-49775 words', 
                '182 page-50050 words', 
                '183 page-50325 words', 
                '184 page-50600 words', 
                '185 page-50875 words', 
                '186 page-51150 words', 
                '187 page-51425 words', 
                '188 page-51700 words', 
                '189 page-51975 words', 
                '190 page-52250 words', 
                '191 page-52525 words', 
                '192 page-52800 words', 
                '193 page-53075 words', 
                '194 page-53350 words', 
                '195 page-53625 words', 
                '196 page-53900 words', 
                '197 page-54175 words', 
                '198 page-54450 words', 
                '199 page-54725 words', 
                '200 page-55000 words'];


       if (Auth::user()->ni_admin )
        {
            $prof_comp_array = 0;
            $list_messages = Message::where('for_admin', 1)->where('unread', 1)->orderBy('created_at', 'desc')->get();
            $messages_no = $list_messages->count();

            $list_bid_accepted = Notification::where('status',0)->where('type',"admin_order_bidPlaced")->orderBy('created_at', 'desc')->get();
            $list_order_message = Notification::where('status',0)->where('type',"admin_order_message")->orderBy('created_at', 'desc')->get();


            // $list_notifications = $list_order_message->toBase()->merge($list_bid_accepted);
            $notifications_no = $list_bid_accepted->count();
            // I used $list_bid_accepted the same as other users so as not to have a difficult time to display them on the layout

            $order_msg_no = $list_order_message->count();

            // $list_notifications = $list_notifications->sortByDesc('created_at');

            $list_tasks = Notification::where('status',0)->where('type',"admin_order_late")->orderBy('created_at', 'desc')->get();
            $number_tasks =$list_tasks->count() ;
         
        }
        else
        {
             
          $prof_comp_array = NotificationController::profileComplete(Auth::user());

          $list_messages = Auth::user()->messages->where('unread',1)->sortByDesc('created_at');
          $messages_no = $list_messages->count();

          $list_bid_accepted = Auth::user()->notifications()->where('status',0)->where('type','order_bid_accepted')->orderBy('created_at', 'desc')->get();
          $list_order_message = Auth::user()->notifications()->where('status',0)->where('type','order_message')->orderBy('created_at', 'desc')->get();

          // $list_notifications = $list_order_message->toBase()->merge($list_bid_accepted);
           $notifications_no = $list_bid_accepted->count();
           $order_msg_no = $list_order_message->count();
          
          // $list_notifications = $list_notifications->sortByDesc('created_at');



          $list_order_late = Auth::user()->notifications()->where('status',0)->where('type','order_late')->orderBy('created_at', 'desc')->get();
          $list_order_revisions = Auth::user()->notifications()->where('status',0)->where('type','order_revision')->orderBy('created_at', 'desc')->get();

          $list_tasks = $list_order_revisions->toBase()->merge($list_order_late);
          $number_tasks =$list_tasks->count() ;

        }
      return view('pages.new_order', 
        [ 
        'site_title' => PagesController::$site_title,
        'page_title' => 'Add New order',
        'page_description' =>'Create a New Order record',
        'list_notifications' =>PagesController::$list_notifications,
        'user_description' => PagesController::$user_description,
        'join_date_text' => PagesController::$join_date_text,
        'version_no' => PagesController::$version_no,
        'writers' => $users,
        'countries' => $countries,
        'subject_options' => $subect_options,
        'type_of_product_options' => $type_of_product_options,
        'word_length_options' => $word_length_options,
        'messages_no' => $messages_no,
        'list_messages' => $list_messages,
        'order_msg_no' => $order_msg_no,
        'list_order_message' => $list_order_message,
        'notifications_no' => $notifications_no,
        'list_bid_accepted' => $list_bid_accepted,
        'list_tasks' => $list_tasks,
        'number_tasks' => $number_tasks,
        'prof_comp_array' => $prof_comp_array,]);   
    }

    public function store_order(Request $request)
    {
        if(!Auth::user()->ni_admin)
        {       
             return back()->with('Error', 'Only Admins can complete that action. Get in touch with one');
                
            
        }

        $this->validate($request,
            ['order_no'=>'required|unique:orders,order_no',
            'type_of_product'=>'required',
            'client_deadline' => 'required'
            ]); 
        $order = new Order;
        $order->type_of_product = $request->type_of_product;
        $order->subject = $request->subject;
        $order->word_length = $request->word_length;
        $order->spacing = $request->spacing;
        $order->academic_level = $request->academic_level;

        // $order->delivery_time = $request->delivery_time;
        // $order->client_delivery_time =$request->client_deadline;
        // // $client_hours =$request->client_deadline;
        // $new_deadline = date("Y-m-d H:i:s",strtotime(sprintf("+%d hours", $hours)));
        // $today= Carbon::now();

        // $order->deadline = Carbon::now()->addHours($request->delivery_time);
        $order->deadline = $request->deadline;
        $order->client_deadline = $request->client_deadline;

        $order->client_country = $request->client_country;
        
        $order->compensation = $request->compensation;
        $order->total = $request->total;
        $order->style = $request->style;
        $order->no_of_sources = $request->no_of_sources;
        $order->title = $request->title;
        $order->instructions = $request->instructions;
        $order->essential_sources = $request->essential_sources;
        $order->suggested_sources = $request->suggested_sources;
        $order->order_no = $request->order_no;
            

        // We check if the request had a file with it. 
        if ($request->file('attachment')) {

            $attachment = $request->file('attachment');
            // $extension = $attachment->getClientOriginalExtension();
            // $original_attachment_name = $attachment->getClientOriginalName();
            $order->attachment ='orderFile_'.$attachment->getClientOriginalName();
            $order->attachment_mimeType =$attachment->getClientMimeType();

            Storage::disk('orders')->put($order->attachment, File::get($attachment));
        }


        // $order->attachment = $request->attachment;
        $order->status = $request->status;
        $order->user_id = $request->writer;


        $order->save();

        // We get the order id and the user_id so that we can save it on the order status reports
        $order->order_id = $order->id;
        $order->user_id = $request->user_id;
        OrderreportsController::store_report($order);

        return redirect('orders/'.$order->id)->with('message','Order has been Added Successfully');
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
          $users = User::where('ni_admin',null)->where('status', '1')->where('verified',1)->get();

          $countries = array("Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegowina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, the Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia (Hrvatska)", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "France Metropolitan", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard and Mc Donald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Lao, People's Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovakia (Slovak Republic)", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "Spain", "Sri Lanka", "St. Helena", "St. Pierre and Miquelon", "Sudan", "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (U.S.)", "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Yugoslavia", "Zambia", "Zimbabwe");
          $subect_options=[null,'Accounting','Advertising/Public Relations','Alternative Dispute Resolution (ADR)/Mediation','Animal/Plant Biology','Anthropology','Archaeology','Architecture','Art','Biology','Business','Chemistry','Children & Young People','Civil Litigation Law','Commercial Law','Commercial Property Law','Communications','Company/Business/Partnership Law','Comparative/Conflict of Laws','Competition Law','Computer Science','Constitutional/Administrative Law','Construction','Construction Law','Contract Law','Counselling','Criminal Justice System/Process (Law)','Criminal Law','Criminal Litigation (Law)','Criminology','Cultural Studies','Dentistry','Design','Drama','Economics','Economics (Social Sciences)','Education','Employment','Employment Law','Engineering','English Language','English Legal System (Law)','English Literature','Environment','Environmental Sciences','Equity & Trusts Law','Estate Management','European (EU) Law','European Studies','Family Law','Fashion','Film Studies','Finance','Finance Law','Food and Nutrition','Forensic Science','French','General Law','Geography','Geology','German','Health','Health & Social Care','Health and Safety','Health Psychology','History','Housing','Human Resource Management','Human Rights','Human Rights Law','Immigration/Refugee Law','Information - Media & Technology Law','Information Systems','Information Technology','Intellectual Property Law','International Commercial Law','International Criminal Law','International Law','International Political Economy','International Relations','International Studies','Jurisprudence (Law)','Land/property Law','Landlord & Tenant/Housing Law','Law of Evidence','Legal Professional Ethics (Law)','Linguistics','Management','Maritime Law','Marketing','Maths','Media','Medical Law','Medical Technology','Medicine','Mental Health','Mental Health Law','Methodology','Music','Nursing','Occupational Therapy','Oil & Gas Law','Other','Paramedic Studies','Pharmacology','Philosophy','Photography','Physical Education','Physics','Physiotherapy','Planning/Environmental Law','Politics','Professional Conduct Law','Psychology','Psychotherapy','Public Administration','Public Law','Quantity Surveying','Real Estate','Research report for management','Security & Risk Management','Social Policy','Social Work','Social Work Law','Sociology','Spanish','Sports Law','Sports Psychology','Sports Science','SPSS','Statitstics','StatisticsTax Law','Teacher Training / PGCETESOL','Theatre Studies','Theology & Religion','Tort Law','Tourism & Hospitality','Town & Country Planning','Translation'];
          $type_of_product_options=[null,'Essay','Term Paper','Research Paper','Coursework','Book Report','Book Review','Movie Review','Research Summary','Dissertation','Thesis','Thesis/Dissertation Proposal','Research Proposal','Dissertation Chapter - Abstract','Dissertation Chapter - Introduction Chapter','Dissertation Chapter - Literature Review','Dissertation Chapter - Methodology','Chapter - Results','Dissertation Chapter - Discussion','Dissertation Services - Editing','Dissertation Services - Proofreading','Formatting','Admission Services - Admission Essay','Admission Services - Scholarship Essay','Admission Services - Personal Statement','Admission Services - Editing','Editing','Proofreading','Case Study','Lab Report','Speech/Presentation','Math/Physics/Economics/Statistics Problems','Computer Science Project','Article','Article Critique|','Annotated Bibliography','Reaction Paper','Memorandum','Real Estate License exam','Multiple Choice Questions (Non-time-framed)','Multiple Choice Questions (Time-framed)','Statistics Project','PowerPoint Presentation','Mind/Concept mapping','Multimedia Project','Simulation report','Problem Solving'];
          $word_length_options=[null,
                '1 page-275 words', 
                '2 page-550 words', 
                '3 page-825 words', 
                '4 page-1100 words', 
                '5 page-1375 words', 
                '6 page-1650 words', 
                '7 page-1925 words', 
                '8 page-2200 words', 
                '9 page-2475 words', 
                '10 page-2750 words', 
                '11 page-3025 words', 
                '12 page-3300 words', 
                '13 page-3575 words', 
                '14 page-3850 words', 
                '15 page-4125 words', 
                '16 page-4400 words', 
                '17 page-4675 words', 
                '18 page-4950 words', 
                '19 page-5225 words', 
                '20 page-5500 words', 
                '21 page-5775 words', 
                '22 page-6050 words', 
                '23 page-6325 words', 
                '24 page-6600 words', 
                '25 page-6875 words', 
                '26 page-7150 words', 
                '27 page-7425 words', 
                '28 page-7700 words', 
                '29 page-7975 words', 
                '30 page-8250 words', 
                '31 page-8525 words', 
                '32 page-8800 words', 
                '33 page-9075 words', 
                '34 page-9350 words', 
                '35 page-9625 words', 
                '36 page-9900 words', 
                '37 page-10175 words', 
                '38 page-10450 words', 
                '39 page-10725 words', 
                '40 page-11000 words', 
                '41 page-11275 words', 
                '42 page-11550 words', 
                '43 page-11825 words', 
                '44 page-12100 words', 
                '45 page-12375 words', 
                '46 page-12650 words', 
                '47 page-12925 words', 
                '48 page-13200 words', 
                '49 page-13475 words', 
                '50 page-13750 words', 
                '51 page-14025 words', 
                '52 page-14300 words', 
                '53 page-14575 words', 
                '54 page-14850 words', 
                '55 page-15125 words', 
                '56 page-15400 words', 
                '57 page-15675 words', 
                '58 page-15950 words', 
                '59 page-16225 words', 
                '60 page-16500 words', 
                '61 page-16775 words', 
                '62 page-17050 words', 
                '63 page-17325 words', 
                '64 page-17600 words', 
                '65 page-17875 words', 
                '66 page-18150 words', 
                '67 page-18425 words', 
                '68 page-18700 words', 
                '69 page-18975 words', 
                '70 page-19250 words', 
                '71 page-19525 words', 
                '72 page-19800 words', 
                '73 page-20075 words', 
                '74 page-20350 words', 
                '75 page-20625 words', 
                '76 page-20900 words', 
                '77 page-21175 words', 
                '78 page-21450 words', 
                '79 page-21725 words', 
                '80 page-22000 words', 
                '81 page-22275 words', 
                '82 page-22550 words', 
                '83 page-22825 words', 
                '84 page-23100 words', 
                '85 page-23375 words', 
                '86 page-23650 words', 
                '87 page-23925 words', 
                '88 page-24200 words', 
                '89 page-24475 words', 
                '90 page-24750 words', 
                '91 page-25025 words', 
                '92 page-25300 words', 
                '93 page-25575 words', 
                '94 page-25850 words', 
                '95 page-26125 words', 
                '96 page-26400 words', 
                '97 page-26675 words', 
                '98 page-26950 words', 
                '99 page-27225 words', 
                '100 page-27500 words', 
                '101 page-27775 words', 
                '102 page-28050 words', 
                '103 page-28325 words', 
                '104 page-28600 words', 
                '105 page-28875 words', 
                '106 page-29150 words', 
                '107 page-29425 words', 
                '108 page-29700 words', 
                '109 page-29975 words', 
                '110 page-30250 words', 
                '111 page-30525 words', 
                '112 page-30800 words', 
                '113 page-31075 words', 
                '114 page-31350 words', 
                '115 page-31625 words', 
                '116 page-31900 words', 
                '117 page-32175 words', 
                '118 page-32450 words', 
                '119 page-32725 words', 
                '120 page-33000 words', 
                '121 page-33275 words', 
                '122 page-33550 words', 
                '123 page-33825 words', 
                '124 page-34100 words', 
                '125 page-34375 words', 
                '126 page-34650 words', 
                '127 page-34925 words', 
                '128 page-35200 words', 
                '129 page-35475 words', 
                '130 page-35750 words', 
                '131 page-36025 words', 
                '132 page-36300 words', 
                '133 page-36575 words', 
                '134 page-36850 words', 
                '135 page-37125 words', 
                '136 page-37400 words', 
                '137 page-37675 words', 
                '138 page-37950 words', 
                '139 page-38225 words', 
                '140 page-38500 words', 
                '141 page-38775 words', 
                '142 page-39050 words', 
                '143 page-39325 words', 
                '144 page-39600 words', 
                '145 page-39875 words', 
                '146 page-40150 words', 
                '147 page-40425 words', 
                '148 page-40700 words', 
                '149 page-40975 words', 
                '150 page-41250 words', 
                '151 page-41525 words', 
                '152 page-41800 words', 
                '153 page-42075 words', 
                '154 page-42350 words', 
                '155 page-42625 words', 
                '156 page-42900 words', 
                '157 page-43175 words', 
                '158 page-43450 words', 
                '159 page-43725 words', 
                '160 page-44000 words', 
                '161 page-44275 words', 
                '162 page-44550 words', 
                '163 page-44825 words', 
                '164 page-45100 words', 
                '165 page-45375 words', 
                '166 page-45650 words', 
                '167 page-45925 words', 
                '168 page-46200 words', 
                '169 page-46475 words', 
                '170 page-46750 words', 
                '171 page-47025 words', 
                '172 page-47300 words', 
                '173 page-47575 words', 
                '174 page-47850 words', 
                '175 page-48125 words', 
                '176 page-48400 words', 
                '177 page-48675 words', 
                '178 page-48950 words', 
                '179 page-49225 words', 
                '180 page-49500 words', 
                '181 page-49775 words', 
                '182 page-50050 words', 
                '183 page-50325 words', 
                '184 page-50600 words', 
                '185 page-50875 words', 
                '186 page-51150 words', 
                '187 page-51425 words', 
                '188 page-51700 words', 
                '189 page-51975 words', 
                '190 page-52250 words', 
                '191 page-52525 words', 
                '192 page-52800 words', 
                '193 page-53075 words', 
                '194 page-53350 words', 
                '195 page-53625 words', 
                '196 page-53900 words', 
                '197 page-54175 words', 
                '198 page-54450 words', 
                '199 page-54725 words', 
                '200 page-55000 words'];

        if (Auth::user()->ni_admin )
        {
            $prof_comp_array = 0;
            $list_messages = Message::where('for_admin', 1)->where('unread', 1)->orderBy('created_at', 'desc')->get();
            $messages_no = $list_messages->count();

            $list_bid_accepted = Notification::where('status',0)->where('type',"admin_order_bidPlaced")->orderBy('created_at', 'desc')->get();
            $list_order_message = Notification::where('status',0)->where('type',"admin_order_message")->orderBy('created_at', 'desc')->get();


            // $list_notifications = $list_order_message->toBase()->merge($list_bid_accepted);
            $notifications_no = $list_bid_accepted->count();
            // I used $list_bid_accepted the same as other users so as not to have a difficult time to display them on the layout

            $order_msg_no = $list_order_message->count();

            // $list_notifications = $list_notifications->sortByDesc('created_at');

            $list_tasks = Notification::where('status',0)->where('type',"admin_order_late")->orderBy('created_at', 'desc')->get();
            $number_tasks =$list_tasks->count() ;
         
        }
        else
        {
             
          Return back()->with('Error', 'Only Admins can complete that action. Get in touch with one');

        }

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
        'order' => $order,
        'countries' => $countries,
        'subject_options' => $subect_options,
        'type_of_product_options' => $type_of_product_options,
        'word_length_options' => $word_length_options,
        'messages_no' => $messages_no,
        'list_messages' => $list_messages,
        'order_msg_no' => $order_msg_no,
        'list_order_message' => $list_order_message,
        'notifications_no' => $notifications_no,
        'list_bid_accepted' => $list_bid_accepted,
        'list_tasks' => $list_tasks,
        'number_tasks' => $number_tasks,
        'prof_comp_array' => $prof_comp_array,
        ]);  
    }

    public function update(Request $request, Order $order)
    {

        $this->validate($request,[
            'order_no'=>'required',
            'order_id'=>'required'
            ]); 

        if($request->type_of_product){
           $order->type_of_product = $request->type_of_product; 
        }
        if($request->subject){
            $order->subject = $request->subject;
        }

        if($request->word_length){
            $order->word_length = $request->word_length;
        }

        if( $request->spacing){
            $order->spacing = $request->spacing;
        }
        
        if($request->academic_level){
            $order->academic_level = $request->academic_level;
        }
        
        if($request->deadline){
            // $hours= $request->delivery_time;
            // $order->deadline = $order->created_at-> addHours($hours);  
            // $order->delivery_time = $request->delivery_time;   

            $order->deadline = $request->deadline;
        }
        
        if($request->client_deadline){

            $order->client_deadline = $request->client_deadline;
        }

        if($request->compensation) {
            $order->compensation = $request->compensation;
        }
        
        if($request->total){
            $order->total = $request->total;
        }

        if($request->style){
            $order->style = $request->style;
        }
        
        if($request->status){
            $order->status = $request->status;
        }
        
        if($request->no_of_sources){
            $order->no_of_sources = $request->no_of_sources ;
        }
        
        if($request->title){
            $order->title = $request->title;
        }
        
        if($request->instructions){
            $order->instructions = $request->instructions;
        }
        
        if($request->essential_sources){
            $order->essential_sources = $request->essential_sources;
        }
        
        if($request->suggested_sources){
            $order->suggested_sources = $request->suggested_sources;
        }
        
        if($request->order_no){
            $order->order_no = $request->order_no;
        }

        if($request->client_country){

            $order->client_country = $request->client_country;
        }

        // We check if the request had a file with it. 
        if ($request->file('attachment')) {

            $attachment = $request->file('attachment');
            // $extension = $attachment->getClientOriginalExtension();
            // $original_attachment_name = $attachment->getClientOriginalName();
            $order->attachment ='orderFile_'.$attachment->getClientOriginalName();
            $order->attachment_mimeType =$attachment->getClientMimeType();

            Storage::disk('orders')->put($order->attachment, File::get($attachment));
        }
          
        
        $order->update();

        OrderreportsController::store_report($request);
         return back()->with('message', 'Order updated Successfully');
    }

    // public function unAssignOrder(Request $request, Order $order)
    // {
    //     $this->validate($request,
    //         [
    //         'order_no' =>'required',
    //         'order_id' =>'required'
    //         ]);

    //     if ($request->status == 'Available') {
    //         $order->status = $request->status;
    //         $order->user_id = null;
    //         $order->compensation = null;

    //     }
    // }

    // Get Writers usernames and emails
    public function getWriters()
    {
        $writers = User::all()->where('ni_admin',null);
        foreach ($writers as $writer => $writer_tu) {
            if ($writer_tu->phone1) {
                $phone = $writer_tu->phone1;
            }
            elseif ($writer_tu->phone2) {
                $phone = $writer_tu->phone2;
            }
            else{
              $phone = 'No phone details found';
            }
            echo $writer_tu->first_name.' '.$writer_tu->last_name.', '.$phone.', <br>';
        }
    }

    public function addMoreTime(Request $request)
    {
       
       
        if (!$request->user()->ni_admin) {
            # code...
            return back()->with('error','You are not allowed to do that. Kindly contact the admin for further assistance');
        }

        $order = Order::findorfail($request->order_id);
        if($request->deadline){
          
            $order->deadline = $request->deadline;
        }
        $order->is_late = null;
        $order->update();

        if($order->user_id){
          $user = $order->user;
          NotificationController::delivery_time_changed($user, $order);
        } 
        


        return back()->with("message","Order delivery time has been updated Successfully! Kindly confirm the deadline and time remaining" );
    }


    public function assign_order(Request $request, Order $order)
    {
        // We check if the Admin is requesting the order to be made Available
        if ($request->status == "Available") {
            $order->user_id = null; //We remove the current writer
            $order->status = $request->status;
            $order->compensation = null;
            $order->is_late = null; //We make sure the order is not marked as late
            $order->update();

            return back()->with('message', 'Order made Available Successfully');

        }
        
        $order->user_id = $request->writer;
        $order->status = $request->status;
        $order->compensation = $request->compensation;
        $order->update();
        OrderreportsController::store_report($request);
        
        // Notification message
        $user = User::find($request->writer);
        
        if ($request->status == "Active")
        {
            NotificationController::bidAcceptednotice($user, $order);
            return back()->with('message', 'Order Assigned Successfully');
        }
        elseif ($request->status == "Active-Revision") 
        {
            NotificationController::orderRevisionnotice($user, $order);
            return back()->with('message', 'Order updated Successfully'); 
        }
        
        // if ($request->status == "Active")
        // {
        //  return back()->with('message', 'Order Assigned Successfully');   
        // }
        // elseif($request->status == "Active-Revision")
        // {
        //     return back()->with('message', 'Order updated Successfully'); 
        // }
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

         if (Auth::user()->ni_admin )
        {
            $prof_comp_array = 0;
            $list_messages = Message::where('for_admin', 1)->where('unread', 1)->orderBy('created_at', 'desc')->get();
            $messages_no = $list_messages->count();

            $list_bid_accepted = Notification::where('status',0)->where('type',"admin_order_bidPlaced")->orderBy('created_at', 'desc')->get();
            $list_order_message = Notification::where('status',0)->where('type',"admin_order_message")->orderBy('created_at', 'desc')->get();


            // $list_notifications = $list_order_message->toBase()->merge($list_bid_accepted);
            $notifications_no = $list_bid_accepted->count();
            // I used $list_bid_accepted the same as other users so as not to have a difficult time to display them on the layout

            $order_msg_no = $list_order_message->count();

            // $list_notifications = $list_notifications->sortByDesc('created_at');

            $list_tasks = Notification::where('status',0)->where('type',"admin_order_late")->orderBy('created_at', 'desc')->get();
            $number_tasks =$list_tasks->count() ;
         
        }
        else
        {
             
          $prof_comp_array = NotificationController::profileComplete(Auth::user());

          $list_messages = Auth::user()->messages->where('unread',1)->sortByDesc('created_at');
          $messages_no = $list_messages->count();

          $list_bid_accepted = Auth::user()->notifications()->where('status',0)->where('type','order_bid_accepted')->orderBy('created_at', 'desc')->get();
          $list_order_message = Auth::user()->notifications()->where('status',0)->where('type','order_message')->orderBy('created_at', 'desc')->get();

          // $list_notifications = $list_order_message->toBase()->merge($list_bid_accepted);
           $notifications_no = $list_bid_accepted->count();
           $order_msg_no = $list_order_message->count();
          
          // $list_notifications = $list_notifications->sortByDesc('created_at');



          $list_order_late = Auth::user()->notifications()->where('status',0)->where('type','order_late')->orderBy('created_at', 'desc')->get();
          $list_order_revisions = Auth::user()->notifications()->where('status',0)->where('type','order_revision')->orderBy('created_at', 'desc')->get();

          $list_tasks = $list_order_revisions->toBase()->merge($list_order_late);
          $number_tasks =$list_tasks->count() ;

        }
        
        return view('pages.search', [ 
    'site_title' => PagesController::$site_title,
    'page_title' => 'You searched for '.$q,
    'page_description' =>PagesController::$page_description='All the orders found for '.$q,
    'list_notifications' =>PagesController::$list_notifications,
    'number_tasks' =>PagesController::$number_tasks,
    'list_tasks' => PagesController::$list_tasks,
    'user_description' => PagesController::$user_description,
    'join_date_text' => PagesController::$join_date_text,
    'version_no' => PagesController::$version_no,
    'orders' => $orders,
    'q'=>$q,
    'messages_no' => $messages_no,
    'list_messages' => $list_messages,
    'order_msg_no' => $order_msg_no,
    'list_order_message' => $list_order_message,
    'notifications_no' => $notifications_no,
    'list_bid_accepted' => $list_bid_accepted,
    'list_tasks' => $list_tasks,
    'number_tasks' => $number_tasks,
    'prof_comp_array' => $prof_comp_array,
    ]);  
    }
    
    public function get_orderFile(Order $order)
    {
        $file = Storage::disk('orders')->get($order->attachment);

        if($file){
            return (new Response($file, 200))
            ->header('Content-Type',$order->attachment_mimeType);
        }
        else
        {
            return back()->with('error',"The file requested can't be found, Kindly contact Suport for further assistance");
        }

    }
    
}
