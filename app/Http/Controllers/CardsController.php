<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\card;

class CardsController extends Controller
{
	private $site_title = 'Academic Research Assistants';
    private $page_title;
    private $page_description;
    Private $notifications_no= '20';
    // private $notification_text; We will use the number of notification in the text instead
    private $list_notifications= 'Late delivery Order # 19989';
    private $number_tasks ='60';
    // private $tasks_text; We will use the number of tasks in the text string instead
    private $list_tasks='Revise order # 1234567';
    private $avatar_img_url ='https://pbs.twimg.com/profile_images/708415697798500353/eseXMvaB_400x400.png';
    private $username = 'Morgan';
    private $user_description='I take my Work seriously';
    private $join_date_text='Nov. 2015';
    private $version_no= '1.1';
    private $year='2016';
    private $active='no'; 

    public function index(){
    	$cards = Card::all();


        // $this->page_title='Cards';
        // $this->page_description='A snapshort of all your Cards';
        // $this->active='active';
       

    	return view('cards.index',[
            'site_title' => $this->site_title,
            'page_title' => $this->page_title='Cards',
            'page_description' =>$this->page_description='A snapshort of all your cards2',
            'notifications_no' =>$this->notifications_no,
            'list_notifications' =>$this->list_notifications,
            'number_tasks' =>$this->number_tasks,
            'list_tasks' => $this->list_tasks,
            'list_notifications' => $this->list_notifications,
            'avatar_img_url' => $this->avatar_img_url,
            'username' => $this->username,
            'user_description' => $this->user_description,
            'join_date_text' => $this->join_date_text,
            'version_no' => $this->version_no,
            'year'=> $this->year,
            // 'active' => $this->active,
            'cards' => $cards,


            ]);

    }
    public function show(Card $card){
    	 // $card = Card::find($id);

    	 // return $id;

    	 return view('cards.show', compact('card'));

    	// return $card;
    }
}
