@extends ('layout')

@section('body')
<? use Carbon\Carbon;
use App\Order;
	use App\User;
	use App\Message;
	 
	 if($page_title=="Mailbox"){
	 	$messages = Message::where('user_id',Auth::user()->id)
      ->groupBy('subject')
      ->orderBy('created_at', 'desc')
      ->get();

	 	}
	 elseif($page_title=="Sentbox"){
	 	$messages = Message::where('sender_id',Auth::user()->id)
    ->groupBy('subject')
    ->orderBy('created_at', 'desc')
    ->get();
	 }
	
	?>
<!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-3">

          <div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Folders</h3>

              <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="box-body no-padding">
              <ul class="nav nav-pills nav-stacked">
                <li class="
                <?if($page_title=="Mailbox"){echo('active');}?>
                "><a href="/mailbox"><i class="fa fa-inbox"></i> Inbox
                  <span class="label label-primary pull-right">{{$unread_messages = Auth::user()->messages->where('unread' ,'1')->count()}}</span></a></li>
                <li class="
                <?if($page_title=="Sentbox"){echo('active');}?>
                "><a href="/sentbox"><i class="fa fa-envelope-o"></i> Sent</a></li>
              </ul>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /. box -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="box box-primary">
            <div class="box-header with-border 
            @if($page_title == "Read Mail"){
            hidden
          } @endif">
              <h3 class="box-title">
              	<?if($page_title=="Sentbox"){echo('Sentbox');}else{echo('Inbox');}?></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
              @if($page_title == "Read Mail")
             <? $emails= Message::where('subject',$message->subject)->get() ?>
			
                            @if(count($emails) >1)
              <div class="mailbox-read-info">
                <h3>{{$message->subject}}</h3>
              </div>
                @foreach($emails as $email)
                <?$sender_info = User::find($email->sender_id);
                    if ($sender_info->ni_admin == 1){
                    	$sender_info->first_name ="Admin";
                    	$sender_info->last_name ="";
                    } ?>
  
                    <div class="mailbox-read-info">
                    <small><b>From: {{$sender_info->first_name}}</b>
                      <span class="mailbox-read-time pull-right">{{$message->created_at}}</span></small>
                  </div>
                   <div class="mailbox-read-message attachment-block margin-bottom">
                    {!!$email->body!!}
                    </div>

                @if($email->user_id == Auth::user()->id)
                <? $email->unread =0;
                  $email->update(); ?>
                  @endif
                @endforeach
                @else
                <div class="mailbox-read-info">
                
                <h5>From: {{$sender_info->first_name}}
                  <span class="mailbox-read-time pull-right">{{$message->created_at}}</span></h5>
              </div>
               <div class="mailbox-read-message with-border attachment-block">
                {!!$message->body!!}
                <? $message->unread =0;
                  $message->update(); ?>
                 </div>
                 
                @endif
              <!-- /.mailbox-read-message -->
              <!-- <div class="box-footer">
              <div class="pull-right">
                <button type="button" class="btn btn-default"><i class="fa fa-reply"></i> Reply</button>
                <button type="button" class="btn btn-default"><i class="fa fa-share"></i> Forward</button>
              </div>
              <button type="button" class="btn btn-default"><i class="fa fa-trash-o"></i> Delete</button>
              <button type="button" class="btn btn-default"><i class="fa fa-print"></i> Print</button>
            </div> -->
        </div>
    </div>
            <div class="box box-success">
            <div class="box-body">
            	{{Form::open(array('url' => '/new-reply', 'method' => 'POST'))}}
              <!-- <form method="post" action="/new-reply"> -->
              <input type="hidden" name="subject" value="{{$message->subject}}">
				<input type="hidden" name="user_id" value="{{Auth::user()->id}}">
				<input type="hidden" name="receiver_id" value="{{$message->sender_id}}">
              <div class="form-group">
                    <textarea id="compose-textarea" name="body" class="form-control" Required="required" style="height: 200px" placeholder="Enter New Reply here" style="width: 100%">
                      
                    </textarea>
              </div>
              <!-- <div class="form-group">
                <div class="btn btn-default btn-file">
                  <i class="fa fa-paperclip"></i> Attachment
                  <input type="file" name="attachment">
                </div>
                <p class="help-block">Max. 32MB</p>
              </div> -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
              <div class="pull-right">
                <!-- <button type="button" class="btn btn-default"><i class="fa fa-pencil"></i> Draft</button> -->
                <button type="submit" id="reply-msg" class="btn btn-primary"><i class="fa fa-envelope-o"></i> Reply</button>
                <!-- </form> -->
                {{Form::close()}}
              </div>
              <!-- <button type="reset" class="btn btn-default"><i class="fa fa-times"></i> Discard</button> -->
            </div>
            <!-- /.box-footer -->
          
              @else
              <div class="table-responsive mailbox-messages">
                <table class="table table-hover table-striped" id="mailbox_table">
                	<thead>
                		<tr>
                			<th>Subject</th>
                			<?if($page_title=="Sentbox"){echo('<th>To</th>');}else{echo('<th>From</th>');}?>

                			<th>Order ID</th>
                			<th>Date</th>
                		</tr>
                	</thead>
                  <tbody>
                  	@foreach($messages as $message)

                  	<?php 
                  	$date_added = Carbon::parse("$message->created_at");
					//$time_now =Carbon::now();
					$timer= $date_added->diffForHumans();
					?>
                  <tr>
                    <td class="mailbox-subject"><b><a href="{{route('message.show',$message->id)}}">{{$message->subject}}</a></b></td>
                    <?
                    if($page_title=="Sentbox")
                    	{$user_id ="user_id";}
                    else{$user_id="sender_id";}; 
                    $sender_info = User::find($message->$user_id);
                    if ($sender_info->ni_admin){
                    	$sender_info->first_name ="Admin";
                    	$sender_info->last_name ="";
                    } ?>
                    <td class="mailbox-name">{{$sender_info->first_name}} {{$sender_info->last_name}}</td>
                    <td class="mailbox-date">{{$message->order_id}}</td>
                    <td class="mailbox-date">{{$timer}}</td>
                  </tr>
                  @endforeach
                  </tbody>
                </table>
                <!-- /.table -->
              </div>
              <!-- /.mail-box-messages -->
              @endif
            </div>
            <!-- /.box-body -->
            <div class="box-footer no-padding">
             
            </div>
          <!-- /. box -->
        

        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
@stop