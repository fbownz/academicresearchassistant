<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use App\Earning_report;
use Carbon\Carbon;

class Earning_reports_controller extends Controller
{
    // store earning_reports
    public static function store_Earning_report(User $user, $total_earnings)
    {
    	$today = Carbon::today()->toDateTimeString();
    	
    	
    		$earning_report = new Earning_report;
			$earning_report->user_id = $user->id;
			$earning_report->total = $total_earnings;
			// $earning_report->orders_done = $user->earnings->where('paid',1)->where('updated_at','>',$user->earning_reports->last()->created_at)->count();
            
			$earning_report->save();
		
    	
    }
}
