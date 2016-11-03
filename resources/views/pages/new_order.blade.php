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
          		<? $message = Session('message'); ?>
	         	<div class="alert alert-info alert-dismissible">
	                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
	                <h4><i class="icon fa fa-info"></i>Success!</h4>
	                {{$message}}
	         	</div>
          	@elseif(Session::has('error'))
          		<? $error = Session('error'); ?>
          		<div class="alert alert-danger alert-dismissible">
	              	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
	                <h4><i class="icon fa fa-ban"></i> Error!</h4>
	                {{$error}}
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
				<!-- this form is used for both Edit and Add new, depending on the Request, check the routes and the if functions in the feilds -->
				
								@if (strpos($_SERVER['REQUEST_URI'], "edit") !== false) 
				<form	
	            					method="post" action="/orders/{{$order->id}}" accept-charset="UTF-8" enctype="multipart/form-data">
	            					{{method_field('PATCH')}}

	            				@elseif (strpos($_SERVER['REQUEST_URI'], "new") !== false)
	            					<!-- method="post" action="/new_order"> -->
	            					{{ Form::open(array('url'=>'/new_order', 'files'=>true)) }}

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
	            					@foreach($type_of_product_options as $option)
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
									@foreach($subject_options as $option)
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
	            					@foreach($word_length_options as $option)
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
	                		<label>Enter the Writer's Deadline:</label> <!-- <span>In hours</span> -->

			                <div class="input-group">
			                  <div class="input-group-addon">
			                    <i class="fa fa-calendar-plus-o"></i>
			                  </div>
			                  <input type="datetime-local" name="deadline" id="deadline" class="form-control"
				                  @if (strpos($_SERVER['REQUEST_URI'], "edit") !== false)
				                  	value="{{$order->deadline}}"> 
				                  @else
				                  Required="Required">
				                  @endif
			                </div>
		                <!-- /.input group -->
	              		</div>
	              		<div class="form-group col-md-6">
	              			{{Form::label('client_deadline', "Enter Client's Deadline:")}}
	              			<div class="input-group">
	              				<div class="input-group-addon">
	              					<i class="fa fa-calendar-check-o"></i>
	              				</div>
	              			<input type="datetime-local" name="client_deadline" id="client_deadline" class="form-control"
			              			@if(strpos($_SERVER['REQUEST_URI'], "edit") !== false)
			              			value="{{$order->client_deadline}}"> 
			              			@else
			              			Required="Required">
			              			@endif
	              			</div>
	              		</div>

	              		<div class="form-group col-md-6">
	              			{{Form::label('client_country', "Select the client's Country")}}
	              			<select type="text" name="client_country" class="form-control" required="required">
	              				@foreach($countries as $country)
	              				<option value="{{$country}}"
	              					@if(strpos($_SERVER['REQUEST_URI'],"edit") !== false)
	              					@if($order->client_country == $country)
	              					Selected ="selected"
	              					@endif
	              					@endif
	              				>{{$country}}</option>
	              				@endforeach
	              			</select>
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
						<div class="form-group col-md-6">
							{{ Form::label('attachment','Select a file to upload with this Order') }}

			                  <div class="input-group">
			                  <div class="input-group-addon">
			                    <i class="glyphicon glyphicon-open-file"></i>
			                  </div>
						  {{ Form::file('attachment',array('class'=>'form-control')) }}
						  @if ($errors->has('file'))
					            <span class="help-block">
					                <strong>{{ $errors->first('file') }}</strong>
					            </span>
			        	  @endif
			        	  </div>
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

		<!-- Column 2 -->
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
					<div class='form-group col-md-12 '>
						<label>Select Order Status</label>
						<select type="text" name="status" class="form-control">
							<? $options=[
							'Available', 
							'Not-Available',
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