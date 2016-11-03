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
          <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Sent Mail</h3>
                
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="table-responsive mailbox-messages">
                <table class="table table-hover table-striped" id="mailbox_table">
                  <thead>
                    <tr>
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
                    <td class="mailbox-name">
                      <a href="/readmail/{{$mail->id}}#m{{$mail->id}}">
                        @if(Auth::user()->ni_admin)
                        To: {{$first_name = User::findorfail($mail->user_id)->first_name}} {{$last_name = User::findorfail($mail->user_id)->last_name}}
                        @elseif($mail->department == 'Support')
                        To: {{$first_name = 'Support'}}
                        @elseif($mail->department == 'Quality Assurance')
                        To: {{$first_name = 'Quality Assurance'}}
                        @elseif($mail->department == 'Billing')
                        To: {{$first_name = 'Billing'}}
                        @elseif($mail->department == null)
                        To: {{$first_name = 'Admin'}}
                        @endif
                      </a>
                    </td>
                    <td class="mailbox-subject">
                      <a href="/readmail/{{$mail->id}}#m{{$mail->id}}">
                      {{$mail->subject}}
                      </a>
                    </td>
                    <td class="mailbox-date">
                      <a href="/readmail/{{$mail->id}}#m{{$mail->id}}"> {{$timer}}</a>
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