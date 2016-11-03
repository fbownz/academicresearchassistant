@extends ('layout')

@section('body')
<? use Carbon\Carbon;
  use App\User;
  use App\Message;
?>
<!-- Main content -->
    <section class="content">
        @if(Session::has('message'))
          <div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                <h4><i class="icon fa fa-info"></i>Success!</h4>
                 <? $message = Session('message'); ?>
                {{$message}}
              </div>

            @elseif(Session::has('error'))
          <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                <h4><i class="icon fa fa-info"></i>Error!</h4>
                 <? $error = Session('error'); ?>
                {{$error}}
              </div>
      @endif
      <div class="row">
        <div class="col-md-3">
          <a href="/compose" class="btn btn-primary btn-block margin-bottom">Compose</a>
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
                  <span class="label label-primary pull-right">{{$unread_msgs->count()}}</span>
                </a>
                </li>
                @if(Auth::user()->ni_admin)
                  <li class="
                  <?if($page_title=="Support"){echo('active');}?>
                  "><a href="/support"><i class="fa fa-envelope-o"></i>Support</a></li>

                  <li class="
                  <?if($page_title=="Quality Assurance"){echo('active');}?>
                  "><a href="/support"><i class="fa fa-envelope-o"></i>Quality Assurance</a></li>

                  <li class="
                  <?if($page_title=="Billing"){echo('active');}?>
                  "><a href="/support"><i class="fa fa-envelope-o"></i>Billing</a></li>
                @endif
                <li class="
                <?if($page_title=="Sent"){echo('active');}?>
                "><a href="/sentbox"><i class="fa fa-envelope-o"></i>Sent</a></li>
                </li>

              </ul>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /. box -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">{{$message->subject}}</h3>
              @if($message->department)
              <span class="mailbox-read-time"><i class="icon fa fa-chevron-right"></i>{{$message->department}} Department</span>
              @endif
            </div>
            <!-- /.box-header -->
            <div class="box-body ">
             <!-- /.mailbox-read-message -->
              <div class="box-group" id="accordion">
                <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                @foreach($mail_thread as $mail)
                <? 
                    if($message->user_id == 0){
                      $message->user_id = $message->sender_id;
                    }
                ?>
                
                <?
                   // Setting up emails and from name views
                    
                      $from_email = User::findorfail($mail->sender_id)->email;
                      $from_name = User::findorfail($mail->sender_id)->first_name.' '.User::findorfail($mail->sender_id)->last_name;
                    if(!Auth::user()->ni_admin){
                        if(!$mail->for_admin && $mail->department == 'Support')
                          {
                            $from_email = 'support@academicresearchassistants.com';
                            $from_name = 'Support Team';
                          }
                        if(!$mail->for_admin && $mail->department == 'Quality Assurance')
                          {
                            $from_email = 'qualityassurance@academicresearchassistants.com';
                            $from_name = 'Quality Assurance Team';
                          }
                        if(!$mail->for_admin && $mail->department == 'Billing')
                          {
                            $from_email = 'billing@academicresearchassistants.com';
                            $from_name = 'Billing Department';
                          }
                        if(!$mail->department && !$mail->for_admin)
                          {
                            $from_email = 'info@academicresearchassistants.com';
                            $from_name = 'Admin';
                          }
                    }
                ?>
                <div class="panel box">
                  <div class="box-header with-border" style="background-color: #d2d6de;">
                    <h5 >
                      @if($mail->unread)
                        <b>
                        <span class="mailbox-read-time pull-right">{{$mail->created_at->format('F j, H:i A')}}</span>
                        <a data-toggle="collapse" data-parent="#accordion" href="#m{{$mail->id}}" style="text-decoration: none; color: #000;">
                          {{$from_name}} &lt;{{$from_email}}&gt;
                        </a>
                        </b>
                      @else
                        <span class="mailbox-read-time pull-right">{{$mail->created_at->format('F j, H:i A')}}</span>
                      <a data-toggle="collapse" data-parent="#accordion" href="#m{{$mail->id}}" style="text-decoration: none; color: #333;">
                        {{$from_name}} &lt;{{$from_email}}&gt;
                      </a>
                      @endif
                    </h5>
                  </div>
                  <div id="m{{$mail->id}}" class="panel-collapse collapse 
                    @if($message->id == $mail->id)
                    in
                    @endif
                    ">
                    <div class="box-body">
                      <div class="mailbox-read-message">
                        {!! $mail->body !!}
                      </div>
                    </div>
                  </div>
                </div>
                @endforeach
              </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer" style="background-color: #d2d6de;">
            
              <h4 class="box-title">Reply</h4>
            
            <!-- /.box-header -->
            <div class="box-body">
              {{ Form::open(array(
                      'url' => '/new-reply',
                      'class' =>'form-horizontal',
                      )) }}
                
                <input type="hidden" name="subject" value="{{$mail->subject}}">
                <input type="hidden" name="receiver_id" value="{{$mail->sender_id}}">
                <input type="hidden" name="department" value="{{$mail->department}}">
                <input type="hidden" name="mail_id" value="{{$mail->id}}}">
              <div class="form-group">
                {!! Form::textarea('body', null, array('id'=>'compose-textarea',
                                                        'class'=>'form-control',
                                                        'required' => 'required',
                                                        'style'=>'height:200px',
                                                        'placeholder'=>'Enter your Message here')) !!}
              </div>
            </div>
              <div class="pull-right">
                <button type="submit" class="btn btn-primary"><i class="fa fa-envelope-o"></i> Send</button>
              </div>
            {{Form::close()}}
          </div>
            </div>
            <!-- /.box-footer -->
          </div>
          <!-- /. box -->
      </div>

      <!-- /.row -->
    </section>
@stop