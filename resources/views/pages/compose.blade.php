@extends ('layout')

@section('body')
    <!-- Main content -->
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
          <a href="/mailbox" class="btn btn-primary btn-block margin-bottom">Back to Inbox</a>

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
                <li>
                <a href="/mailbox"><i class="fa fa-inbox"></i> Inbox
                  <span class="label label-primary pull-right">{{$unread_messages = Auth::user()->messages->where('unread' ,'1')->count()}}</span>
                </a>
                </li>
                <li><a href="/sentbox"><i class="fa fa-envelope-o"></i>Sent</a></li>
              </ul>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /. box -->
          
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          @if(!Auth::user()->ni_admin)
          <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title">Compose a New Message</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              
              <div class="form-group">
                {{ Form::open(array(
                      'url' => '/compose',
                      'class' =>'form-horizontal',
                      )) }}
                {!! Form::select('department', array('Support' => 'Support',
                                                  'Quality Assurance' => 'Quality Assurance',
                                                  'Billing' => 'Billing'),
                                                  null, 
                                                  array('class'=>'form-control',
                                                        'required' => 'required',
                                                        'placeholder'=>'Select Department:')) 
                !!}
              </div>
              <div class="form-group">
                {!! Form::text('subject', null, array('class'=>'form-control',
                                                        'required' => 'required',
                                                        'placeholder'=>'Subject')) 
                !!}
              </div>
              <div class="form-group">
                {!! Form::textarea('body', null, array('id'=>'compose-textarea',
                                                        'class'=>'form-control',
                                                        'required' => 'required',
                                                        'style'=>'height:300px',
                                                        'placeholder'=>'Enter your Message here')) !!}
              </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
              <div class="pull-right">
                <button type="submit" class="btn btn-primary"><i class="fa fa-envelope-o"></i> Send</button>
              </div>
            </div>
            <!-- /.box-footer -->
            {{Form::close()}}
          </div>
          @elseif(Auth::user()->ni_admin)
          <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title">Compose a New Message</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="form-group">
                {{ Form::open(array(
                      'url' => '/compose',
                      'class' =>'form-horizontal',
                      )) }}

            <select name="writer" class="form-control" required ="required">
              <option selected="selected" value="">Select a Writer</option>
              <option value="active writers">Active Writers</option>
              @foreach($writers as $writer)
              <option value="{{$writer->id}}">
                {{$writer->first_name}} {{$writer->last_name}}
              </option>
              @endforeach
            </select>
              </div>
              <div class="form-group">
                {!! Form::select('department', array('Support' => 'Support',
                                                  'Quality Assurance' => 'Quality Assurance',
                                                  'Billing' => 'Billing'),
                                                  null, 
                                                  array('class'=>'form-control',
                                                        'required' => 'required',
                                                        'placeholder'=>'Select Department:')) 
                !!}
              </div>
              <div class="form-group">
                {!! Form::text('subject', null, array('class'=>'form-control',
                                                                    'required' => 'required',
                                                                    'placeholder'=>'Subject:')) !!}
              </div>
              <div class="form-group">
                {!! Form::textarea('body', null, array('id'=>'compose-textarea',
                                                        'class'=>'form-control',
                                                        'required' => 'required',
                                                        'style'=>'height:300px',
                                                        'placeholder'=>'Enter your Message here')) !!}
              </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
              <div class="pull-right">
                <button type="submit" class="btn btn-primary"><i class="fa fa-envelope-o"></i> Send</button>
              </div>
            </div>
            <!-- /.box-footer -->
            {{Form::close()}}
          </div>
          @endif
          <!-- /. box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>



@stop