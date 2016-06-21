@extends('layout')

@section('body')
<section class="content">
	<div class="row">
		<div class="col-md-8">
			<!-- Display Form or submission Errors if any -->
			
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
			@endif
		<div class="box box-success">
			<div class="box-header with-border">
				@if (strpos($_SERVER['REQUEST_URI'], "edit") !== false) 
					<h4>{{$order->order_no}} Details</h4>
					<small>Was last updated on <b>{{$order -> updated_at->format('F j, Y H:i A')}}</b> </small> 
            					<!-- <a class="btn btn-success" href="/new_order">
            						Add new Order
            					</a> -->
					@else
					<h4><i class="fa fa-fw fa-file-text-o"></i>Enter new order Details</h4>
				@endif	
				<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                		</button>
					</div>
				
			</div>
			<!-- this form is used for both Edit and Add new, depending on the Request, check the routes and the if funcitons in the feilds -->
			<form
							@if (strpos($_SERVER['REQUEST_URI'], "edit") !== false) 
            					
            					method="post" action="/orders/{{$order->id}}">
            					{{method_field('PATCH')}}

            				@elseif (strpos($_SERVER['REQUEST_URI'], "new") !== false)
            					method="post" action="/new_order">

            				@endif
				<div class="box-body">
					{{csrf_field()}}
					@if (strpos($_SERVER['REQUEST_URI'], "edit") !== false)
					<input type="hidden" name="order_id" value="{{$order->id}}">
					@endif
					<input type="hidden" name="user_id" value="{{Auth::user()->id }}">
					<div class="form-group col-md-6">
						<label>Enter Order ID:</label>
						<input type="text" name="order_no" class="form-control" 
						@if (strpos($_SERVER['REQUEST_URI'], "edit") !== false) 
            					value="{{$order->order_no}}"

            				@endif 
            				Required="required">
            			
					</div>
					<div class="form-group col-md-6">
						<label>Select Type of Product:</label>
						<select name="type_of_product" class="form-control" Required="required" >
						<? $options=[null,
						'Essay',
						'Term Paper',
						'Research Paper',
						'Coursework',
						'Book Report',
						'Book Review',
						'Movie Review',
						'Research Summary',
						'Dissertation',
						'Thesis',
						'Thesis/Dissertation Proposal',
						'Research Proposal',
						'Dissertation Chapter - Abstract',
						'Dissertation Chapter - Introduction Chapter',
						'Dissertation Chapter - Literature Review',
						'Dissertation Chapter - Methodology',
						'Chapter - Results',
						'Dissertation Chapter - Discussion',
						'Dissertation Services - Editing',
						'Dissertation Services - Proofreading',
						'Formatting',
						'Admission Services - Admission Essay',
						'Admission Services - Scholarship Essay',
						'Admission Services - Personal Statement',
						'Admission Services - Editing',
						'Editing',
						'Proofreading',
						'Case Study',
						'Lab Report',
						'Speech/Presentation',
						'Math/Physics/Economics/Statistics Problems',
						'Computer Science Project',
						'Article',
						'Article Critique|',
						'Annotated Bibliography',
						'Reaction Paper',
						'Memorandum',
						'Real Estate License exam',
						'Multiple Choice Questions (Non-time-framed)',
						'Multiple Choice Questions (Time-framed)',
						'Statistics Project',
						'PowerPoint Presentation',
						'Mind/Concept mapping',
						'Multimedia Project',
						'Simulation report',
						'Problem Solving'
						];?>
            					@foreach($options as $option)
            					<option value="{{$option}}"
            					@if (strpos($_SERVER['REQUEST_URI'], "edit") !== false) 
            					@if ($option == $order->type_of_product)
            					Selected="selected"
            					@endif
            					@endif
            					> {{$option}} </option>
            					@endforeach
							
						</select>
					</div>
					<div class="form-group col-md-6">
						<label>Select Subject</label>
						<select name="subject" class="form-control" Required="required" >
						<? $options=[
						null,
						'Accounting',
						'Advertising/Public Relations',
						'Alternative Dispute Resolution (ADR)/Mediation',
						'Animal/Plant Biology',
						'Anthropology',
						'Archaeology',
						'Architecture',
						'Art',
						'Biology',
						'Business',
						'Chemistry',
						'Children & Young People',
						'Civil Litigation Law',
						'Commercial Law',
						'Commercial Property Law',
						'Communications',
						'Company/Business/Partnership Law',
						'Comparative/Conflict of Laws',
						'Competition Law',
						'Computer Science',
						'Constitutional/Administrative Law',
						'Construction',
						'Construction Law',
						'Contract Law',
						'Counselling',
						'Criminal Justice System/Process (Law)',
						'Criminal Law',
						'Criminal Litigation (Law)',
						'Criminology',
						'Cultural Studies',
						'Dentistry',
						'Design',
						'Drama',
						'Economics',
						'Economics (Social Sciences)',
						'Education',
						'Employment',
						'Employment Law',
						'Engineering',
						'English Language',
						'English Legal System (Law)',
						'English Literature',
						'Environment',
						'Environmental Sciences',
						'Equity & Trusts Law',
						'Estate Management',
						'European (EU) Law',
						'European Studies',
						'Family Law',
						'Fashion',
						'Film Studies',
						'Finance',
						'Finance Law',
						'Food and Nutrition',
						'Forensic Science',
						'French',
						'General Law',
						'Geography',
						'Geology',
						'German',
						'Health',
						'Health & Social Care',
						'Health and Safety',
						'Health Psychology',
						'History',
						'Housing',
						'Human Resource Management',
						'Human Rights',
						'Human Rights Law',
						'Immigration/Refugee Law',
						'Information - Media & Technology Law',
						'Information Systems',
						'Information Technology',
						'Intellectual Property Law',
						'International Commercial Law',
						'International Criminal Law',
						'International Law',
						'International Political Economy',
						'International Relations',
						'International Studies',
						'Jurisprudence (Law)',
						'Land/property Law',
						'Landlord & Tenant/Housing Law',
						'Law of Evidence',
						'Legal Professional Ethics (Law)',
						'Linguistics',
						'Management',
						'Maritime Law',
						'Marketing',
						'Maths',
						'Media',
						'Medical Law',
						'Medical Technology',
						'Medicine',
						'Mental Health',
						'Mental Health Law',
						'Methodology',
						'Music',
						'Nursing',
						'Occupational Therapy',
						'Oil & Gas Law',
						'Other',
						'Paramedic Studies',
						'Pharmacology',
						'Philosophy',
						'Photography',
						'Physical Education',
						'Physics',
						'Physiotherapy',
						'Planning/Environmental Law',
						'Politics',
						'Professional Conduct Law',
						'Psychology',
						'Psychotherapy',
						'Public Administration',
						'Public Law',
						'Quantity Surveying',
						'Real Estate',
						'Security & Risk Management',
						'Social Policy',
						'Social Work',
						'Social Work Law',
						'Sociology',
						'Spanish',
						'Sports Law',
						'Sports Psychology',
						'Sports Science',
						'SPSS',
						'StatisticsTax Law',
						'Teacher Training / PGCETESOL',
						'Theatre Studies',
						'Theology & Religion',
						'Tort Law',
						'Tourism & Hospitality',
						'Town & Country Planning',
						'Translation'
						];?>
            					@foreach($options as $option)
            					<option value="{{$option}}"
            					@if (strpos($_SERVER['REQUEST_URI'], "edit") !== false) 
            					@if ($option == $order->subject)
            					Selected="selected"
            					@endif
            					@endif
            					> {{$option}}</option>
            					@endforeach
						</select>
					</div>

					<div class="form-group col-md-6">
						<label>Select Word length</label>
						<select name="word_length" class="form-control" Required="required"> 
							<? $options=[null,
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
							'200 page-55000 words', 
							];?>
            					@foreach($options as $option)
            					<option value="{{$option}}"
            					@if (strpos($_SERVER['REQUEST_URI'], "edit") !== false) 
            					@if ($option == $order->word_length)
            					Selected="selected"
            					@endif
            					@endif
            					 > {{$option}}</option>
            					@endforeach
						</select>
					</div>
					<div class="form-group col-md-6">
						<label>Select Spacing </label>
						<select name="spacing" class="form-control" placeholder="Select Spacing Here..." Required="required" >
							
							<? $options=[
							null,
							'Double',
							'Single'
							];?>
            					@foreach($options as $option)
            					<option value="{{$option}}"
            					@if (strpos($_SERVER['REQUEST_URI'], "edit") !== false) 
            					@if ($option == $order->spacing)
            					Selected="selected"
            					@endif
            					@endif
            					 > {{$option}}</option>
            					@endforeach
						</select>
					</div>
					<div class="form-group col-md-6">
						<label>Select Academic Level </label>
						<select name="academic_level" class="form-control" Required="required">
							<? $options=[null,
							'Undergraduate',
							'High School',
							'Masters',
							'PHD'
							];?>
            					@foreach($options as $option)
            					<option value="{{$option}}"
            					@if (strpos($_SERVER['REQUEST_URI'], "edit") !== false) 
            					@if ($option == $order->academic_level)
            					Selected="selected"
            					@endif
            					@endif
            					 > {{$option}}</option>
            					@endforeach
						</select>
					</div>
					<div class="form-group col-md-6">
                		<label>Enter the Deadline:</label> <span>In hours</span>

		                <div class="input-group">
		                  <div class="input-group-addon">
		                    <i class="fa fa-calendar"></i>
		                  </div>
		                  <input type="number" name="delivery_time" id="delivery_time"class="form-control"
		                  @if (strpos($_SERVER['REQUEST_URI'], "edit") !== false) 
		            					value="{{$order->delivery_time}}"


		            				@endif Required="Required">
		                </div>
	                <!-- /.input group -->
              		</div>
              		<div class="form-group col-md-6">
              			{{Form::label('client_deadline', 'Enter Client Deadline')}}
              			{{Form::number('client_deadline', $order->client_delivery_time, array('class'=>'form-control',
              														  'required' =>'required',
              														  'placeholder' =>'Enter the client delivery time in hours'))}}
              		</div>

					<div class="form-group col-md-6">
						<label>Enter The Total</label> <small> (In $)</small>
						<input type="text" name="total" class="form-control" placeholder="Enter The Total Here..." 
						@if (strpos($_SERVER['REQUEST_URI'], "edit") !== false) 
            					value="{{$order->total}}"


            				@endif Required="Required">
					</div>
					
					<div class="form-group col-md-6">
						<label>Select Reference style</label>
						<select type="text" name="style" class="form-control" Required="Required" >
						<? $options=['APA','MLA','Chicago','Harvard','Oxford','Turabian',
            					'Vancouver','CBE','Other'];?>
            					@foreach($options as $option)
            					<option value="{{$option}}"
            					@if (strpos($_SERVER['REQUEST_URI'], "edit") !== false) 
            					@if ($option == $order->style)
            					Selected="selected"
            					@endif
            					@endif
            					 > {{$option}}</option>
            					@endforeach
						</select>
					</div>
					<div class="form-group col-md-6">
						<label>Enter no of sources</label>
						<input type="number" name="no_of_sources" class="form-control" placeholder="Enter no of sources Here..." 
						@if (strpos($_SERVER['REQUEST_URI'], "edit") !== false) 
            					value="{{$order->no_of_sources}}"


            				@endif Required="Required">
					</div>
					<div class="form-group col-md-12">
						<label>Enter Attachment's URL here</label>
						<input type="text" name="attachment" class="form-control" placeholder="http://45.35.151.135/~writemyessay/wp-content/uploads/gravity_forms/2-117cddc86d5d1ba3100da15129516b21/2016/03/Final-paper-outline-11.docx"
						 @if (strpos($_SERVER['REQUEST_URI'], "edit") !== false) 
            					value="{{$order->attachment}}"


            				@endif>
            				<span class="text-small">Replace the "https://writemyacademicessay.com/" with "http://45.35.151.135/~writemyessay/"</span>
					</div>
					<div class="form-group col-md-12">
						<label>Enter Order Title:</label>
						<input type="text" name="title" class="form-control" placeholder="Enter Title Here..."
						@if (strpos($_SERVER['REQUEST_URI'], "edit") !== false) 
            					value="{{$order->title}}"


            				@endif>
					</div>
					<div class="col-md-12 pad">
						<label>Enter Instructions</label>
						<textarea class="textarea form-control" name="instructions" placeholder="Enter Instructions here" style="width: 100%; 
						height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">
						@if (strpos($_SERVER['REQUEST_URI'], "edit") !== false) 
            					{!!$order->instructions!!}


            				@endif
						</textarea>
					</div>
					<div class="form-group col-md-12">
						<label>Enter Essential Sources</label>
						<textarea class="textarea form-control" name="essential_sources" placeholder="Enter Essential Sources here" style="width: 100%; 
						height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">
						@if (strpos($_SERVER['REQUEST_URI'], "edit") !== false) 
            					{!!$order->essential_sources!!}


            				@endif
						</textarea>
					</div>
					<div class="form-group col-md-12">
						<label>Enter Suggested Sources</label>
						<textarea class="textarea form-control" name="suggested_sources" placeholder="Enter Suggested Sources here" style="width: 100%; 
						height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">
						@if (strpos($_SERVER['REQUEST_URI'], "edit") !== false) 
            					{!!$order->suggested_sources!!}


            				@endif
						</textarea>
					</div>
					
				</div>
				
			
		</div>
		</div>
		<div class="col-md-4">
			<div class="box box-success">
				<div class="box-header with-border">
					<h4><i class="fa fa-money"></i>
						Compensation and Status
					</h4>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                		</button>
					</div>
				</div>
				<div class="box-body">
					<div class="form-group col-md-12">
						<label>Compensation</label> <br>
						<small>The total amount to be paid to the writer</small>
						<input type="number" name="compensation" class="form-control" Required="required" placeholder="Enter amount here..."
						@if (strpos($_SERVER['REQUEST_URI'], "edit") !== false) 
            					value="{{$order->compensation}}"
            				@endif >
					</div>
					<div class="form-group col-md-12">
						<label>Select Order Status</label>
						<select type="text" name="status" class="form-control">
							<? $options=[
							'Available', 
							'Not Available',
							];?>
        					@foreach($options as $option)
        					<option value="{{$option}}"
        					@if (strpos($_SERVER['REQUEST_URI'], "edit") !== false) 
        					@if ($option == $order->status)
        					Selected="selected"
        					@endif
        					@endif
        					 > {{$option}}</option>
        					@endforeach
						</select>
					</div>
				</div>
				<div class="box-footer">
					<div class="form-group">
						
							@if (strpos($_SERVER['REQUEST_URI'], "edit") !== false) 
            					<a class="btn btn-success" href="/orders/{{$order->id}}">
            						View Order
            					</a>
            					<button class="btn btn-success" type="submit">
            						Update Order
            					</button>
            					<a class="btn btn-danger pull-right" href="../../orders/delete/{{$order->id}}">
            						Delete Order
            					</a>

            				@endif
							@if (strpos($_SERVER['REQUEST_URI'], "new_order") !== false) 
            					<button class="btn btn-success" type="submit" title="Add">
            						Add Order
            					</button>

            				@endif

						

					</div>
				</div>
			</div>
			</form>	
		</div>
	</div>
</section>

@stop