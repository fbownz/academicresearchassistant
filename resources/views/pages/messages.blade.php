@extends ('layout')

@section('body')
<? use Carbon\Carbon;
  use App\User;
  use App\Message; ?>
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
                  "><a href="#"><i class="fa fa-envelope-o"></i>Support</a></li>

                  <li class="
                  <?if($page_title=="Quality Assurance"){echo('active');}?>
                  "><a href="#"><i class="fa fa-envelope-o"></i>Quality Assurance</a></li>

                  <li class="
                  <?if($page_title=="Billing"){echo('active');}?>
                  "><a href="#"><i class="fa fa-envelope-o"></i>Billing</a></li>
                @endif
                <li class="
                <?if($page_title=="Sent"){echo('active');}?>
                "><a href="/sentbox"><i class="fa fa-envelope-o"></i>Sent</a></li>
                </li>

              </ul>
            </div>  
          </div>
          <!-- Labels Box -->
          <div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Labels</h3>

              <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="box-body no-padding">
              <ul class="nav nav-pills nav-stacked">
                <li><a href="#"><i class="fa fa-star text-purple"></i> Quality Assurance</a></li>
                <li><a href="#"><i class="fa fa-star text-fuchsia"></i> Support</a></li>
                <li><a href="#"><i class="fa fa-star text-olive"></i> Billing</a></li>
              </ul>
            </div>
              <!-- /.box-body -->
          </div>
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Unread Messages</h3>
                <div class="box-tools pull-right">
                  <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                  </button>
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="table-responsive mailbox-messages">
                <table class="table table-hover table-striped" id="unread_table">
                  <thead>
                    <tr>
                      <th>Label</th>
                      <th>Name</th>
                      <th>Subject</th>
                      <th>Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($unread_msgs as $mail)

                    <?php 
                    $date_added = Carbon::parse("$mail->created_at");
                    //$time_now =Carbon::now();
                    $timer= $date_added->diffForHumans();
                    ?>
                 <tr>
                    <td class="mailbox-star">
                      <a href="/readmail/{{$mail->id}}#m{{$mail->id}}"><i class="fa fa-star
                        <? 
                          if($mail->department == 'Support')
                          {
                            echo 'text-fuchsia';
                          }
                        if( $mail->department == 'Quality Assurance')
                          {
                            echo 'text-purple';
                          }
                        if( $mail->department == 'Billing')
                          {
                            echo 'text-olive';
                          }
                        // if(!$mail->for_admin)
                        //   {
                        //     echo 'text-fuchsia';
                        //   }
                        ?>
                        "></i></a>
                    </td>
                    <td class="mailbox-name">
                      <a href="/readmail/{{$mail->id}}#m{{$mail->id}}">
                        <b>
                        @if(Auth::user()->ni_admin)
                        {{$first_name = User::findorfail($mail->sender_id)->first_name}}
                        @elseif($mail->department == 'Support')
                        {{$first_name = 'Support'}}
                        @elseif($mail->department == 'Quality Assurance')
                        {{$first_name = 'Quality Assurance'}}
                        @elseif($mail->department == 'Billing')
                        {{$first_name = 'Billing'}}
                        @elseif($mail->department == null)
                        {{$first_name = 'Admin'}}
                        @endif
                        </b>
                      </a>
                    </td>
                    <td class="mailbox-subject">
                      <a href="/readmail/{{$mail->id}}#m{{$mail->id}}">
                      <b>{{$mail->subject}}</b>
                      </a>
                    </td>
                    <td class="mailbox-date">
                      <b><a href="{{route('message.show',$mail->id)}}"> {{$timer}}</a></b>
                    </td>
                  </tr>
                  @endforeach
                  </tbody>
                </table>
                <!-- /.table -->
              </div>
              <!-- /.mail-box-messages -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer no-padding">
             
            </div>
          <!-- /. box -->
        </div>
        <!-- Start of All Messages -->
          <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">All Messages</h3>
                <div class="box-tools pull-right">
                  <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                  </button>
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="table-responsive mailbox-messages">
                <table class="table table-hover table-striped" id="mailbox_table">
                  <thead>
                    <tr>
                      <th>Label</th>
                      <th>Name</th>
                      <th>Subject</th>
                      <th>Date</th>
                    </tr>
                  </thead>
                  <tbody>
                  	@foreach($messages as $mail)

                  	<?php 
                  	$date_added = Carbon::parse("$mail->created_at");
          					//$time_now =Carbon::now();
          					$timer= $date_added->diffForHumans();
          					?>
                 <tr>
                    <td class="mailbox-star">
                      <a href="/readmail/{{$mail->id}}#m{{$mail->id}}"><i class="fa fa-star-o
                        <? 
                          if($mail->department == 'Support')
                          {
                            echo 'text-fuchsia';
                          }
                        if( $mail->department == 'Quality Assurance')
                          {
                            echo 'text-purple';
                          }
                        if( $mail->department == 'Billing')
                          {
                            echo 'text-olive';
                          }
                        if(!$mail->department)
                          {
                            echo 'text-fuchsia';
                          }
                        ?>
                        "></i></a>
                    </td>
                    <td class="mailbox-name">
                      <a href="/readmail/{{$mail->id}}#m{{$mail->id}}">
                        @if(Auth::user()->ni_admin)
                        {{$first_name = User::findorfail($mail->sender_id)->first_name}}
                        @elseif($mail->department == 'Support')
                        {{$first_name = 'Support'}}
                        @elseif($mail->department == 'Quality Assurance')
                        {{$first_name = 'Quality Assurance'}}
                        @elseif($mail->department == 'Billing')
                        {{$first_name = 'Billing'}}
                        @elseif($mail->department == null)
                        {{$first_name = 'Admin'}}
                        @endif
                      </a>
                    </td>
                    <td class="mailbox-subject">
                      <a href="/readmail/{{$mail->id}}#m{{$mail->id}}">
                      {{$mail->subject}}
                      </a>
                    </td>
                    <td class="mailbox-date">
                      <a href="{{route('message.show',$mail->id)}}"> {{$timer}}</a>
                    </td>
                  </tr>
                  @endforeach
                  </tbody>
                </table>
                <!-- /.table -->
              </div>
              <!-- /.mail-box-messages -->
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