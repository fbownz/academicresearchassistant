@extends ('layout')

@section('body')
<!-- Main content -->
    <section class="content">
      <? use Carbon\Carbon;
      ?> 

      <div class="row">
        <div class="col-md-3">

          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile">
              <img class="profile-user-img img-responsive img-circle" src="{{$user->prof_pic_url}}" alt="Your profile picture">

              <h3 class="profile-username text-center">{{$user->first_name}} {{$user->last_name}}</h3>
              <!-- We'll use the space to define User levels and status -->
              <!-- <p class="text-muted text-center">Software Engineer</p> -->

              <ul class="list-group list-group-unbordered">
                <li class="list-group-item">
                  <b>Bids</b> <a href="/order_bids" class="pull-right">{{$user->bids()->whereHas('order',function($q)
                                                                      {
                                                                        $q->where('status','Available');
                                                                      })->count()}}
                                </a>
                </li>
                <li class="list-group-item">
                  <b>Orders completed</b> <a class="pull-right">{{$user->orders->where('approved', 1)->count()}}</a>
                  
                </li>
              </ul>

              <a href="#settings" class="btn btn-primary btn-block"><b>Edit Profile</b></a>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

          <!-- About Me Box -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">About Me</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <strong><i class="fa fa-book margin-r-5"></i> Education</strong>

              <p class="text-muted">
                <!-- B.S. in Computer Science from the University of Tennessee at Knoxville -->
                {{$user->academic_level}}
              </p>

              <hr>

              <strong><i class="fa fa-map-marker margin-r-5"></i> Location</strong>

              <p class="text-muted">{{$user->address}}</p>
              <hr>
              <strong> <i class="fa fa-calendar margin-r-5"></i> Member since</strong>
              <p class="text-muted">{{$user->created_at->format('F j, Y')}}</p>
              <hr>
              <strong><i class="fa fa-file-text-o margin-r-5"></i> Notes</strong>

              <p>
                {{$user->description}}
              </p>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
         @if(count($errors))
      @foreach($errors->all() as $error)
              <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                <h4><i class="icon fa fa-ban"></i> Error!</h4>
                {{$error}}
              </div>
              @endforeach

            @elseif(Session::has('message'))
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
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class = "active" style="background-color:#337ab7"><a href="#experience" data-toggle="tab"><h4>Order Activity</h4></a></li>
              <li style="background-color:#337ab7"><a href="#billing" data-toggle="tab"><h4>Bank details</h4></a></li>
              <li style="background-color:#337ab7"><a href="#settings" data-toggle="tab"><h4>Edit Profile</h4></a></li>
            </ul>
            <div class="tab-content">
              <div class="active tab-pane" id="experience">
                <div class="box box-solid">
                  <div class="box-body">
                     <div class="box-header">
                    <h4 class="box-title text-olive">A summary of all Orders you have done so far</h4>
                  </div>
                  <div class="form-group">
                    <div class="col-md-4">
                      <strong>Orders in Revision</strong>
                    </div>
                    <div class="text-muted col-md-8">
                      <strong>{{$user->orders->where('status', 'Active-Revision')->count()}}</strong>
                    </div>
                  </div>  
                  <div class="form-group">
                    <div class="col-md-4">
                      <strong>Orders Active</strong>
                    </div>
                    <div class="text-muted col-md-8">
                      <strong>{{$user->orders->where('status','Active')->count()}}</strong>
                    </div>
                  </div> 
                  <div class="form-group">
                    <div class="col-md-4">
                      <strong>Orders Delivered</strong>
                    </div>
                    <div class="text-muted col-md-8">
                      <strong>{{$user->orders->where('is_complete', 1)->count()}}</strong>
                    </div>
                  </div> 
                  <div class="form-group">
                    <div class="col-md-4">
                      <strong>Orders Approved</strong>
                    </div>
                    <div class="text-muted col-md-8">
                      <strong>{{$user->orders->where('approved', 1)->count()}}</strong>
                    </div>
                  </div>  
                  <div class="form-group">
                    <div class="col-md-4">
                      <strong>Late Orders</strong>
                    </div>
                    <div class="text-muted col-md-8">
                      <strong>{{$user->orders->where('is_late', 1)->count()}}</strong>
                    </div>
                  </div>    
                @if($subject_infos)
                <hr>
                <div class="box-title">Summary of Orders done
                </div>
                  @foreach($subject_infos as $subject_info)
                  <!-- We create colors for the labels --> 
                   @if($subject_info->total/$user->orders->count() < 0.25)
                      <? $label_color = 'label-warning'; ?>
                    @elseif($subject_info->total/$user->orders->count() < 0.50 )
                      <? $label_color = 'label-primary'; ?>
                    @elseif($subject_info->total/$user->orders->count() < 0.75)
                      <? $label_color = 'label-info'; ?>
                    @elseif($subject_info->total/$user->orders->count() > 0.75 )
                      <? $label_color = 'label-success'; ?>
                   @endif
                  <div class="col-md-6">
                  <div class="label {{$label_color}} ">{{$subject_info->subject}}</div>
                  <span class="text-muted">{{$subject_info->total}}</span>
                  </div>
                  @endforeach
                
                @endif
                  </div>
                </div>
              </div>
                <!-- /. tab-pane Order Activity -->

              <div class="tab-pane" id="settings">
                <div class="box-header">
                    <h4 class="box-title text-olive">Fill in the Form Below to update your Profile</h4>
                  </div>
                {!! Form::model($user,
                array('url'=>'/user/update',
                      'class' =>'form-horizontal',
                      'files'=>'true')) !!}
                      {{method_field('PATCH')}}
                <div class="form-group">
                  {!! Form::label('first_name', 'First Name', array('class' => 'col-sm-2 control-label text-olive')) !!}
                  
                  <div class="col-sm-4">
                  {!! Form::text('first_name', $user->first_name, array('class' => 'form-control',
                                                                        'placeholder'=> 'First Name',
                                                                        'required'=>'required'))
                  !!}
                  </div>

                  {!! Form::label('last_name', 'Last Name', array('class'=>'col-sm-2 control-label text-olive')) !!}
                  
                  <div class="col-sm-4">
                    {!! Form::text('last_name', $user->last_name, array('class' => 'form-control',
                                                                      'placeholder' => 'First Name',
                                                                      'required'=>'required')) 
                  !!}
                  </div>
                  
                </div>

                <div class="form-group">
                  {!! Form::label('email', 'Email', array('class' => 'col-sm-2 control-label text-olive')) !!}

                  <div class="col-sm-4">
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-envelope-square text-olive"></i>
                      </div>
                      {!! Form::email('email', $user->email, array('class' => 'form-control',
                                                                  'placeholder' => 'Enter Email',
                                                                  'required'=>'required')) !!}
                    </div>
                      <span class="text-muted text-olive">This is the email that we use to send you notifications and communication</span>
                  </div>

                  {!! Form::label('username', 'UserName', array('class' => 'col-sm-2 control-label text-olive')) !!}
                  <div class="col-sm-4">
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-user text-olive"></i>
                      </div>
                    {!! Form::text('name', $user->name, array('class' => 'form-control',
                                                                  'placeholder' => 'Enter your Username here',
                                                                  'required'=>'required')) !!}
                    </div>
                  </div>
                </div>

                <div class="form-group">

                  {!! Form::label('phone1', 'Phone 1', array('class' => 'col-sm-2 control-label text-olive')) !!}
                  <div class="col-sm-4">
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-phone text-olive"></i>
                      </div>
                    {!! Form::text('phone1', $user->phone1, array('class' => 'form-control',
                                                                  'placeholder' => '+2541234567890',
                                                                  'required'=>'required')) !!}
                    </div>
                  </div>

                  {!! Form::label('phone2', 'Phone 2', array('class' => 'col-sm-2 control-label text-olive')) !!}
                  <div class="col-sm-4">
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-mobile text-olive"></i>
                      </div>
                    {!! Form::text('phone2', $user->phone2, array('class' => 'form-control',
                                                                  'placeholder' => '+2541234567890')) !!}
                    </div>
                  </div>

                </div>
                <div class="form-group">

                  {!! Form::label('address', 'Address', array('class' => 'col-sm-2 control-label text-olive')) !!}
                  <div class="col-sm-4">
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-building text-olive"></i>
                      </div>
                      {!! Form::text('address', $user->address, array('class'=>'form-control',
                                                                      'placeholder' => 'Emter your Address: City, town and zipcode',
                                                                      'required'=>'required')) !!}
                    </div>
                  </div>

                      {!! Form::label('country', 'Country', array('class' => 'col-sm-2 control-label text-olive')) !!}
                      <div class="col-sm-4">
                        <div class="input-group">
                          <div class="input-group-addon">
                            <i class="fa fa-building text-olive"></i>
                          </div>
                          {!! Form::text('country', $user->country, array('class'=>'form-control',
                                                                          'placeholder' => 'Enter your Country',
                                                                          'required'=>'required')) !!}
                        </div>
                      </div>
                    </div>
                <hr>

                <div class="form-group">
                  {!! Form::label('academic_level', 'Academic Level', array('class' => 'col-sm-2 control-label text-olive')) !!}
                  <div class="col-sm-4">
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-language text-olive"></i>
                      </div>
                      {!! Form::select('academic_level', array('Undergraduate' => 'undergraduate',
                                                                'High School' => 'high school',
                                                                'Masters' => 'masters',
                                                                'PHD' => 'phd'
                                                                ), 
                                                              $user->academic_level, 
                                                              array('class'=>'form-control',
                                                              'required'=>'required',
                                                              'placeholder' => 'Select your Academic level')) !!}
                    </div>
                  </div>
                
                  {!! Form::label('language', 'Language', array('class' => 'col-sm-2 control-label text-olive')) !!}
                  <div class="col-sm-4">
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-language text-olive"></i>
                      </div>
                      {!! Form::text('language', $user->language, array('class'=>'form-control',
                                                                      'placeholder' => 'Enter your Language',
                                                                      'required'=>'required')) !!}
                    </div>
                  </div>
                </div>
                <hr>
                <div class="form-group">
                  {!! Form::label('description', 'Description', array('class' => 'col-sm-2 control-label text-olive')) !!}
                  <div class="col-sm-8">
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-sticky-note text-olive"></i>
                      </div>
                      {!! Form::textarea('description', $user->description, array('class'=>'form-control',
                                                                              'placeholder' => 'In less than 140 characters tell us a bit about yourself',
                                                                              'rows' => '3',
                                                                              'required' =>'required')) !!}
                    </div>
                  </div>
                </div>
                <!-- Computer programs & billing fields to come later on -->
                <hr>
                <div class="form-group">
                  <label class="col-sm-3 control-label pull-left text-olive" for="prof_pic">
                    
                    @if($user->prof_pic_url !== null)
                      <img src="{{$user->prof_pic_url}}" class="user-image" width="100" alt="{{Auth::user()->first_name}} Image">
                      @else
                      Upload a new Prof Pic
                    @endif
                  </label>
                  <div class="col-sm-9">
                  {!! Form::file('prof_pic', null, array('class' => 'form-control')) !!}
                  <div class="text-muted text-olive">
                  Upload a new profile Image
                  </div>
                  </div>
                </div>
                <hr>
                <div class="form-group">
                <label class="col-sm-3 control-label text-olive" for="picha_ya_id">
                  <!-- <img src="{{$user->picha_ya_id_url}}" class="user-image" alt="photo of id"> -->
                  @if($user->picha_ya_id)
                   <? $dl='id' ?>
                    <a href="/user/dl/{{$dl}}" class=" btn btn-success">
                    <i class="icon fa fa-download"></i>Download your ID
                  </a>
                  
                    @else
                    Upload a scanned copy of your ID
                    @endif
                </label>
                <div class="col-sm-9">
                  {!! Form::file('picha_ya_id', null, array('class' => 'form-control',
                                                            'required' =>'required')) !!}
                  <div class="text-muted text-olive">
                  Upload a scanned copy of your id
                  </div>
                </div>
                </div>

                <hr>
                <div class="form-group">
                  <label class="control-label col-sm-3 text-olive" for="resume">
                    @if($user->resume !== null)
                    <? $dl = 'cv'?>
                    <a href="/user/dl/{{$dl}}" class=" btn btn-success">
                    <i class="icon fa fa-download"></i> Download your CV
                  </a>
                  
                    @else
                    Upload your CV
                    @endif
                  </label>
                  <div class="col-sm-9">
                  {!! Form::file('resume', null, array('class' => 'form-control',
                                                      'required' =>'required')) !!}
                  <div class="text-muted text-olive">
                  Update your CV
                  </div>
                  </div>
                </div>
                <hr>
                <div class="form-group">
                  <label class="control-label col-sm-4 text-olive" for="resume">
                    @if($user->certificate !== null)
                    <? $dl = 'cert'?>
                    <a href="/user/dl/{{$dl}}" class=" btn btn-success">
                    <i class="icon fa fa-download"></i>
                    Download your certificate
                    </a>
                    @else
                      Degree or Academic Certificate
                    @endif
                  </label>
                <div class="col-sm-8">
                  {!! Form::file('certificate', null, array('class' => 'form-control',
                                                                          'required' =>'required')) !!}
                  <div class="text-muted text-olive">
                  Click to upload a scanned copy of Degree/Certificate
                  </div>
                </div>
                </div>
                <hr>

                <div class="form-group">
                  <div class="col-sm-offset-2 col-sm-10 ">
                    {!! Form::submit('Update Profile', array('class'=>'btn btn-success'))!!}
                  </div>
                  
                </div>
                {!! Form::close() !!}
              </div>
              <!-- /.tab-pane Edit profile -->

              <div class="tab-pane" id="billing">
                <div class="box box-solid">
                  @if($user->b_details !== null)
                    @foreach($user->b_details as $b_detail)
                    <div class="box-body">
                      <div class="col-md-4">
                        <strong>Bank Name</strong>
                      </div>
                      <div class="text-muted col-md-8">
                        <strong>{{$b_detail->b_name}}</strong>
                      </div>
                      <div class="col-md-4">
                        <strong>Bank Branch</strong>
                      </div>
                      <div class="text-muted col-md-8">
                        <strong>{{$b_detail->b_b_name}}</strong>
                      </div>
                      <hr>
                      <div class="col-md-4">
                        <strong>Account Name</strong>
                      </div>
                      <div class="text-muted col-md-8">
                        <strong>{{$b_detail->a_name}}</strong>
                      </div>
                      <hr>
                      <div class="col-md-4">
                        <strong>Account Number</strong>
                      </div>
                      <div class="text-muted col-md-8">
                        <strong>{{$b_detail->a_number}}</strong>
                      </div>
                      <a href="#edit" class="btn btn-success col-sm-4">Edit Bank Account</a>
                      <a href="/user/billing/{{$b_detail->id}}/delete/" class="btn btn-danger col-sm-4 pull-right">Delete Bank Account</a>
                    </div>
                    @endforeach
                  @endif
                  @if($user->b_details->count() < 1)
                  <div class="box-header">
                    <h4 class="box-title text-olive">Add your Bank Details</h4>
                  </div>
                <div class="box-body">
                    {!! Form::open(array(
                    'url' => '/user/billing',
                    'class' =>'form-horizontal')) !!}
                      <div class="form-group">
                        {!! Form::label('b_name', 'Bank Name', array('class'=>'col-md-4 control-label')) !!}
                        <div class="col-md-8">
                        {!! Form::text('b_name', null, array('class'=>'form-control',
                                                                          'required' => 'required',
                                                                          'placeholder'=>'Enter your Bank Name')) !!}
                        </div>
                      </div>
                       <div class="form-group">
                        {!! Form::label('b_b_name', 'Bank branch name', array('class'=>'col-md-4 control-label')) !!}
                        <div class="col-md-8">
                        {!! Form::text('b_b_name', null, array('class'=>'form-control',
                                                                          'required' => 'required',
                                                                          'placeholder'=>'Enter your Bank branch Name')) !!}
                        </div>
                      </div>
                       <div class="form-group">
                        {!! Form::label('a_name', 'Registered Name on bank account', array('class'=>'col-md-4 control-label')) !!}
                        <div class="col-md-8">
                        {!! Form::text('a_name', null, array('class'=>'form-control',
                                                                          'required' => 'required',
                                                                          'placeholder'=>'Enter the name registered on the account')) !!}
                        </div>
                      </div>
                       <div class="form-group">
                        {!! Form::label('a_number', 'Account Number', array('class'=>'col-md-4 control-label')) !!}
                        <div class="col-md-8">
                        {!! Form::number('a_number', null, array('class'=>'form-control',
                                                                          'required' => 'required',
                                                                          'placeholder'=>'Enter the Account Number',
                                                                          'type'=>'number')) !!}
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                          {!! Form::submit('Add a Bank Account', array('class'=>'btn btn-primary'))!!}
                        </div>
                        
                      </div>
                      {!! Form::close() !!}
                  </div>
                  @endif
                  
                </div>
                <!-- Edit Bank Detail -->
               @if($user->b_details->count() > 0)
                  <div class="box box-primary collapsed-box">
                      <div class="box-header with-border">
                        <h4 class="box-title">Edit Bank Details</h4>

                        <div class="box-tools pull-right">
                          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                          </button>
                        </div>
                        <!-- /.box-tools -->
                      </div>
                      <!-- /.box-header -->
                      <div class="box-body">
                        {{ Form::open(array('url' => '/user/edit_billing',
                                            'class' =>'form-horizontal',)
                                      ) 
                        }}
                        
                    {{method_field('PATCH')}}

                      <div class="form-group">
                        {!! Form::label('b_name', 'Bank Name', array('class'=>'col-md-4 control-label')) !!}
                        <div class="col-md-8">
                        {!! Form::text('b_name', $b_name, array('class'=>'form-control',
                                                                          'required' => 'required',
                                                                          'placeholder'=>'Enter your Bank Name')) !!}
                        </div>
                      </div>
                       <div class="form-group">
                        {!! Form::label('b_b_name', 'Bank branch name', array('class'=>'col-md-4 control-label')) !!}
                        <div class="col-md-8">
                        {!! Form::text('b_b_name', $b_b_name, array('class'=>'form-control',
                                                                          'required' => 'required',
                                                                          'placeholder'=>'Enter your Bank branch Name')) !!}
                        </div>
                      </div>
                       <div class="form-group">
                        {!! Form::label('a_name', 'Registered Name on bank account', array('class'=>'col-md-4 control-label')) !!}
                        <div class="col-md-8">
                        {!! Form::text('a_name', $a_name, array('class'=>'form-control',
                                                                          'required' => 'required',
                                                                          'placeholder'=>'Enter the name registered on the account')) !!}
                        </div>
                      </div>
                       <div class="form-group">
                        {!! Form::label('a_number', 'Account Number', array('class'=>'col-md-4 control-label')) !!}
                        <div class="col-md-8">
                        {!! Form::number('a_number', $a_number, array('class'=>'form-control',
                                                                          'required' => 'required',
                                                                          'placeholder'=>'Enter the Account Number',
                                                                          'type'=>'number')) !!}
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                          {!! Form::submit('Update your Bank Account', array('class'=>'btn btn-primary'))!!}
                        </div>
                        
                      </div>
                      {!! Form::close() !!}
                      </div>
                      <!-- /.box-body -->
                  </div>
                @endif  
              </div>
              <!-- /. tab-pane billing -->
            
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

    </section>
  @stop