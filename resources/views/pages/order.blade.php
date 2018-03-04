@extends('layout')

@section('body')
<?php
	use Carbon\Carbon;
	use App\User;
	$deadline = Carbon::parse("$order->deadline");
	$client_deadline = Carbon::parse("$order->client_deadline");
	//$time_now =Carbon::now();
	$timer= $deadline->diffForHumans();

	?>
<section class="content">
	<div class="row">
		@if($order->status == 'Available' && ! Auth::User()->ni_admin )
		<div class="col-md-12">
		@else
		<div class="col-md-8">
		@endif
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
                <h4><i class="icon fa fa-warning"></i>Error!</h4>
                 <? $error = Session('error'); ?>
                {{$error}}
              </div>
			@endif

			<!-- Bid on this order -->
			@if ($order->status == "Not-Available")
					<div class="box bg-yellow">
						<div class="box-body">
							This order is currently Not Available for bidding.
						</div>
					</div>
					@endif
			@if($order->status == "Available" || $order->status == "Unpaid" && Auth::user()->ni_admin !== 1 && Auth::user()->verified == 1 && Auth::user()->status == "1" )
				<div class="box box-success" id="bidonthis">
					<div class="box-header with-border">
						<h4><i class="fa fa-pencil"></i>
							Bid on this Order
						</h4>
						<div class="box-tools pull-right">

							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
	                		</button>
						</div>
					</div>
					{{ Form::open(array('url'=>'bid-order')) }}
					<div class="box-body">
						<div class="callout callout-default">
	                		<p><h4>Important! Your bid should include</h4><ol><li>A brief Introduction of how you'll complete the paper</li><li>How much time you will take to complete the paper</li><li>Your qualifications and reason why the cient should assign you the paper</li></ol></p>
              			</div>
							<input type="hidden" name="order_id" value="{{$order->id}}">
							<input type="hidden" name="user_id" value="{{Auth::user()->id}}">
							<input type="number" class="form-control" name="bid_ammount" Required="required" placeholder="Enter your total bid ammount here in $..." max="{{$max_bid}}"/>
							<textarea type="text" class="form-control" name="bid_msg" Required="required" placeholder="Enter your bid message..." rows="4"></textarea>
					</div>
					<div class="box-footer">
						{{ Form::submit('Bid on this job',array('class'=>'btn btn-success')) }}
					</div>

					  {{ Form::close() }}
				</div>

			@endif

			@if(Auth::user()->ni_admin == 1 || Auth::user()->id == $order->user_id)
				@if (count($order->notes)>0)
					<div class="box box-success direct-chat direct-chat-primary " id="order-message">
						<div class="box-header with-border">
							<h4><i class="fa fa-envelope-o"></i>
								Order Messages
							</h4>
							<div class="box-tools pull-right">
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
		                		</button>
							</div>
						</div>
						<div class="box-body ">
							<div class="direct-chat-messages">
								@foreach($order->notes as $note)
								<? $postedat = Carbon::parse($note->created_at);
								$when = $postedat->diffForHumans() ;
								$user = User::find($note->user_id);
								if($user->domain){
									$msg_profpic = "data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEASABIAAD/2wBDAAUEBAUEAwUFBAUGBgUGCA4JCAcHCBEMDQoOFBEVFBMRExMWGB8bFhceFxMTGyUcHiAhIyMjFRomKSYiKR8iIyL/2wBDAQYGBggHCBAJCRAiFhMWIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiIiL/wAARCABLAEsDASIAAhEBAxEB/8QAGwAAAgIDAQAAAAAAAAAAAAAAAAEGBwIFCAT/xAAuEAABAwMCBAYCAQUAAAAAAAABAAIDBAURITEGEkFhBxMiUXGBkaHBFCMyM0L/xAAUAQEAAAAAAAAAAAAAAAAAAAAA/8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAwDAQACEQMRAD8A6+6I09kAI6IEcY1wFXnE/Hvkzuo7CYpMZEtSW8w+G9D8rLxGvT4YorRTux5zPMqHA68udG/BwSfgKtcNQbKmv12pKQ09JcKmKEu5sMfjB7Hot9aPEC40GWXIf18RGnO7le09PVjUfKh+UdNUF+2i6QXi0wVtNo2UatJyWOG7T3XvVJcI3CspOJKKnpKl8cFTURtmjGrXjPUfrKu0aZ3QGEk0kD6IOyANEHZBT/iDRGl4rdMCS2riEmp2I9JA/A/KiZGCpj4kPeeKImv/ANbaZvJ9k5/ah6BYR0QdUdMIM4QTURtax7yXgcjM8ztdhjqV0JSlxo4C6EwOMbcwuOTHp/jnrhc8B7onNkjeWPYQ5rmnBBGxHddB2+d1Va6SoeCHzQse75IBKD0oQhAIQhBB+PuHprjSx3KkLS+iicJWOOOaPfI7jX89lVYXQ1XTR1dHNTTZEU7HRuxuARgqga2imttwqKOqGJoHlju/cdiNftB50dEDdB0yTsgkfCvC8nEVS58sjI6GB4E2vrd1wB0z7q6Gta1rWtAa1owANgFEuB+HprNbpKmrdietDT5Q2jaMkZ76qXIDCMIGyWUDCFiXNYxznuDWt1LnHAAUXufHlmoCWwSPrZh/zTj0/bjp+MoJTjJ01VIcW3IXbimsmjwYmO8ph9w3TP2cle+78e3O5wywQNio6eQcpEeXPI6jmP8ACim2yAGhQdc+yEDZBeXDV3hvNippongysY2OZnVjwMHPzuPlbrK57o66qt1UJ6CokgmGnMx2M9j7jsVNrZ4lVMYay8UrZ27edB6H/bdj+kFnbpLV2m/W69R81vqGueBl0TvS9o7j+RkLaZQUffuJa2/TO855iowfRTMPpA6E+57laRI7lLKDLZBOEZRlA0jqUJdUD2090Y7pFPKDOGaWnnZNTyPimjOWPYcEFWJQ+JojoIWV9HPLUtb/AHJInBrXH3Axoq3G6z5R7IP/2Q==";
								}
								else{
									$msg_profpic = $user->prof_pic_url;
								}
								$s_d = $user->earnings()->count() - $user->fines()->count();
								if (!$s_d) {
									$r_5 = 0;
								}
								else{
					            	$r = $s_d/$user->earnings()->count();
					            	$r_5 = round($r*5,2);
								}
								?>
								<div class="direct-chat-msg
									@if($user->ni_admin || $user->domain)
									right
									@endif
									">
									<div class="direct-chat-info clearfix">
					                    <span class="direct-chat-name text-light-blue
					                    @if($user->ni_admin)
											pull-right
											@else
											pull-left
											@endif
					                    ">

					                    @if($user->ni_admin)
				                    		@if(Auth::user()->ni_admin)
				                    			{{$user->first_name}} {{$user->last_name}}
				                    		@else
				                    		{{$user->first_name}}
				                    		@endif

										@else
											{{$user->name}}
										@endif </span> 
										<span class="text-green @if($user->ni_admin)
											display-none
											@else
											pull-left
											@endif">
			                    			&nbsp;<b>{{$r_5}}</b><i class="fa fa-star"></i>
			                    		</span>
											
					                    <span class="direct-chat-timestamp @if($user->ni_admin)
											pull-left
											@else
											pull-right
											@endif"><!-- {{$note->created_at->format('F j, Y H:i A')}} -->
											{{$when}}
										</span>
			                  		</div>
									<img class="direct-chat-img" src="{{$msg_profpic}}" alt="{{$user->id}} Prof Pic">
									<div class="direct-chat-text">
						                    {!! $note->body !!}
					                </div>
					                @if($note->attachment_name)
						                <div class="attachment-block clearfix">
						                		<a href="/order/msg_file/{{$note->original_attachment_name}}">
						                		<i class="fa fa-file success"></i>
						                			{{$note->original_attachment_name}}
						                		</a>
						                </div>
					                @endif
								</div>
		                    	@endforeach
		                    </div>
		            	</div>
					</div>
				@endif
				<!-- Order Notes-->
				<div class="box box-success">
					<div class="box-header with-border">
						<h4><i class="fa fa-pencil"></i>
							@if(Auth::user()->ni_admin)
							Send a message to both Writer and Client
							@else
							Send a message to the client on this order
							@endif
						</h4>
						<div class="box-tools pull-right">

							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
	                		</button>
						</div>
					</div>
					{{ Form::open(array('url'=>'/new-note', 'files'=>true)) }}
					<div class="box-body">
						<input type="hidden" name="order_id" value="{{$order->id}}">
						<input type="hidden" name="user_id" value="{{Auth::user()->id}}">

						<div class="form-group">
	                		{!! Form::textarea('body', null, array('id'=>'compose-textarea',
	                                                        'class'=>'form-control',
	                                                        'required' => 'required',
	                                                        'style'=>'height:150px',
	                                                        'placeholder'=>'Enter your Message here'))
	                        !!}
	              		</div>

						@if(Auth::user()->ni_admin)
						<div class="form-group col-md-6">
						  {{ Form::label('msg_file','Select a File to send with this message') }}
						  {{ Form::file('msg_file',array('class'=>'form-control')) }}
						  @if ($errors->has('file'))
					            <span class="help-block">
					                <strong>{{ $errors->first('file') }}</strong>
					            </span>
			        	  @endif
					  	</div>
					  	@endif

					</div>
					<div class="box-footer">
						{{ Form::submit('Add Message',array('class'=>'btn btn-success')) }}
					</div>

					  {{ Form::close() }}

				</div>

			@endif


			<div class="box box-success">
				<div class="box-header with-border">

					<h4 class="text-green"><i class="fa fa-edit"></i>
						{{$order->order_no}} Was placed on <b>{{$order -> created_at->format('F j, Y H:i A')}}</b>
					</h4>
					<h5>And was last updated on {{$order -> updated_at->format('F j, Y H:i A')}} is currently
					@if($order->approved == 1 && $order->is_late !== 1)
						<span class="text-light-blue">Approved</span>
						@elseif($order->approved == 1 && $order->is_late == 1)
						<span class="text-light-blue">Approved and late fee aplied</span>
						@elseif($order->approved !=1 && $order->is_late !==1)
						<span class="text-light-blue">{{$order->status}}</span>
						@elseif($order->approved !=1 && $order->is_late ==1)
						<span class="text-red lead">{{$order->status}} and Late!</span>

					@endif
					</h5>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                		</button>
					</div>
				</div>
				<div class="box-body">
					<!-- I separate the layout into 2 colums -->

						<div class="col-md-12">
							<div class="col-md-5 col-sm-6"><strong>Deadline:</strong></div>
						    <div class="col-md-7 col-sm-6">{{$deadline->format('F j, Y H:i A')}}</div>
							<div class="col-md-5 col-sm-6"><strong>Time Remaining:</strong></div>
							@if($order->approved !=1 && $order->is_late ==1)
								<div class="col-md-7 col-sm-6 text-red lead"><i class="fa fa-clock-o"></i> {{$timer}}</div>
							@else()
								<div class="col-md-7 col-sm-6 text-green"><i class="fa fa-clock-o"></i> {{$timer}}</div>
							@endif

						    <div class="col-md-5 col-sm-6"><strong>Compensation Total:</strong></div>
					        @if($order->user_id)
							<div class="col-md-7 col-sm-6 text-green">${{$order->compensation}}</div>
							@else()
							<div class="col-md-7 col-sm-6 text-green">${{$bid_compensation}}</div>
							@endif
						    @if($order->type_ofwork)
						    	<div class="col-md-5 col-sm-6"><strong>Type of work:</strong></div>
						    <div class="col-md-7 col-sm-6">{{$order->type_ofwork}}</div>
						    @endif
						    <div class="col-md-5 col-sm-6"><strong>Pages:</strong><small>(275 Words/Page)</small></div>
						    <div class="col-md-7 col-sm-6">{{$order->word_length}}, <strong>{{$order->spacing}} Spaced</strong></div>
						    <div class="col-md-5 col-sm-6"><strong>Number of Sources:</strong></div>
					        <div class="col-md-7 col-sm-6">{{$order->no_of_sources}}</div>
					        @if($order->type_of_product)
					        <div class="col-md-5 col-sm-6"><strong>Order Type:</strong></div>
					        <div class="col-md-7 col-sm-6">{{$order->type_of_product}}</div>
					        @endif
					        <div class="col-md-5 col-sm-6"><strong>Topic:</strong></div>
					        <div class="col-md-7 col-sm-6">{{$order->subject}}</div>
					        <div class="col-md-5 col-sm-6"><strong>Academic Level:</strong></div>
					        <div class="col-md-7 col-sm-6">{{$order->academic_level}}</div>
					        <div class="col-md-5 col-sm-6"><strong>Reference Style:</strong></div>
					        <div class="col-md-7 col-sm-6">{{$order->style}}</div>
					        <div class="col-md-5 col-sm-6"><strong>Language:</strong></div>
					        <div class="col-md-7 col-sm-6">English</div>
						        @if(Auth::user()->ni_admin)

						        	<div class="col-md-5 col-sm-6"><strong>Client's Deadline:</strong></div>
							        @if($order->approved !== 1 && $order->is_late ==1)
										<div class="col-md-7 col-sm-6 text-red lead"><i class="fa fa-clock-o"></i> {{$client_deadline->format('F j, Y H:i A')}}</div>

							        @else
							        	<div class="col-md-7 col-sm-6 text-green"><i class="fa fa-clock-o"></i> {{$client_deadline->format('F j, Y H:i A')}}</div>
							        @endif
						      		<div class="col-md-5 col-sm-6"><strong>Payment Status</strong></div>
					      			 @if($order->client_ID && $order->transactions->count())
					      			 	@if($order->transactions->last()->responseCode == "Y")
					      			 		<div class="col-md-5 col-sm-6 text-green"><strong>Paid: ${{$order->transactions->last()->total}}</strong></div>
					      			 	@else
					      			 		<div class="col-md-5 col-sm-6 text-red"><strong>Payment UnSuccessful!</strong></div>
					      			 	@endif
					      			 @elseif(!$order->client_ID)
					      			 	<div class="col-md-5 col-sm-6">Added manually!</div>
				      			 	 @else
				      			 	 <div class="col-md-5 col-sm-6 text-red"><strong>Unpaid!</strong></div>
					      			 @endif

						        @endif
					      		<div class="col-md-5 col-sm-6"><strong>Country of Origin</strong></div>
					      		<div class="col-md-5 col-sm-6">{{$order->client_country}}</div>
						</div>

				</div>
				<div class="box-footer">

							<div class="col-md-12">
								@if($order->attachment)
									@if($order->id < 540)
										<a href="{{$order->attachment}}" class=" btn btn-success">
											<i class="icon fa fa-download"></i>
											Click here to Download File Attachment
										</a>
									@else
									<div class="col-md-12">
										<a href="/orderFile/{{$order->id}}" class="btn btn-success">
											<i class="icon fa fa-download"></i>
											{{$order->attachment}}
										</a>
									</div>
									@endif

								@endif
								@if($order->files)
									@foreach($order->files as $file)
										<div class="col-md-12" style="padding-bottom: 5px">
										<a href="{{$file->link}}" class="btn btn-success">
											<i class="icon fa fa-download"></i>
											{{$file->name}}
										</a>
									</div>
									@endforeach

								@endif
								<div class="col-sm-12"><strong>Title:</strong></div>
						        <div class="col-sm-12 attachment-block">{{$order->title}}</div>
						        @if ($order->instructions)
	                            <div class="col-sm-12"><strong>Instructions:</strong></div>
	                            <div class="col-sm-12 attachment-block">{!!$order->instructions!!}</div>
	                            @endif
	                            @if ($order->essential_sources)
							    <div class="col-sm-12"><strong>Essential Sources:</strong></div>
	                            <div class="col-sm-12 attachment-block">{!!$order->essential_sources!!}</div>
							    @endif
							    @if ($order->suggested_sources)
	                            <div class="col-sm-12"><strong>Suggested Sources:</strong></div>
	                            <div class="col-sm-12 attachment-block">{!!$order->suggested_sources!!}</div>
	                            @endif
                            </div>

					</div>
			</div>



			@if (Auth::user()->ni_admin ==1 && count($order->Order_reports)>0)
				<div class="box box-default box-solid">
					<div class="box-header with-border">
						<h4><i class="fa fa-hourglass"></i>
							Order Status Activity
						</h4>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
	                		</button>
						</div>
					</div>
					<div class="box-body">
						<div class="attachment-block">
							@if($order->client_ID)
								<? $client = User::findorfail($order->client_ID); ?>
								Client: <strong>{{$client->email}}</strong>
							@endif
						</div>
						@foreach($order->Order_reports as $Order_report)
						<? $user = User::find($Order_report->user_id); ?>
						@if($Order_report->created_at->diffInMinutes($order->created_at,false) > -2 )
						<div class="attachment-block">Added by {{$user->first_name}} {{$user->last_name}}
							on {{$Order_report->updated_at->format('F j, Y H:i A')}}
						</div>
						@else
	                            <div class="attachment-block">Marked as {{$Order_report->order_status}} by

	                            {{$user->first_name}} {{$user->last_name}}
	                            @if($Order_report->writer_assigned)
	                            and assigned to {{User::find($Order_report->writer_assigned)->first_name}}
	                            @endif
	                             on {{$Order_report->updated_at->format('F j, Y H:i A')}} </div>
	                     @endif
	                    @endforeach
					</div>

				</div>
			@endif

			@if($order->status == 'Available')
				<div class="box box-success">
			@else
				<div class="box box-success collapsed-box">
			@endif
		            <div class="box-header with-border">
		              <h3 class="box-title">Bids placed on this Order {{count($bids)}}</h3>

		              <div class="box-tools pull-right">
		                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
		                </button>
		              </div>
		              <!-- /.box-tools -->
		            </div>
	            	<!-- /.box-header -->

		            <div class="box-body">
		              @foreach($bids as $bid)
						<?
							$bid_user = $bid->user;
							$s_d = $bid_user->earnings()->count() - $bid_user->fines()->count();
							if (!$s_d) {
								$r_5 = 0;
							}
							else{
				            $r = $s_d/$bid_user->earnings()->count();
				            $r_5 = round($r*5,2);
							}

							if ($bid->id & 1)
							{
								$a = "left" ;
								$b = "right";
							}

							else
							{
								$a = "right";
								$b = "left";
							}
						?>
						<div class="box-body direct-chat-msg {{$a}}">
							<div class="direct-chat-info clearfix">
			                    <span class="direct-chat-name control-label text-light-blue pull-{{$a}}">
			                    	<a href="/writer/{{$bid_user->id}}">
			                    	@if(Auth::user()->ni_admin ==1)
			                    		{{$bid_user->first_name}} {{$bid_user->last_name}}
			                    	@else
			                    		{{$bid_user->name}}
		                    		@endif
			                    	</a>
			                    	<br>
			                    </span>
			                    <span class="text-green pull-{{$a}}">
			                    	<b>{{$r_5}}</b><i class="fa fa-star"></i>
			                    </span>

			                    <span class="direct-chat-timestamp pull-{{$b}}">{{$bid->created_at->format('F j, Y H:i A')}}</span>
		                  	</div>
							<img class="direct-chat-img" src="{{$bid_user->prof_pic_url}}" alt="{{$bid_user->id}} Prof Pic">
							<div class="direct-chat-text">
				                    ${{$bid->bid_ammount}}<br/>
				                    {{$bid->bid_msg}}
			              	</div>
		                      @if($bid->user_id == Auth::user()->id)
		                      <a class="btn btn-danger pull-right btn-xs" href="../../bids/delete/{{$bid->id}}">
									Delete
								</a>
								@elseif(Auth::user()->ni_admin)
								<a class="btn btn-danger pull-right btn-xs" href="/bids/delete/{{$bid->id}}">
									Delete
								</a>
							@endif

						</div>
					  @endforeach
		            </div>
		            <!-- /.box-body -->
	            </div>

			@if(Auth::user()->ni_admin && count($order->fines))

				<div class="box box-danger box-solid"  id="fines">
					<div class="box-header with-border">
						<h4><i class="icon fa fa-warning"></i>
							Order Fines!
						</h4>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
	                		</button>
						</div>
					</div>
					<div class="box-body">
						@foreach($order->fines as $fine)
						<div class="callout callout-danger">
		                	<h5>Fine #{{$fine->id}}</h5>
		                	<strong>Reason for fine</strong><br>
					        {{$fine->reason}}<br>
							<strong>Total Fee fined</strong><br>
					        -${{$fine->total_fine}}<br>
							<strong>Writer Fined</strong><br>
					        {{$fine->user->first_name}}
			            </div>
			            <a class="btn btn-danger pull-right btn-xs" href="../../fine/delete/{{$bid->id}}">
									Remove Fine
								</a>
						@endforeach
					</div>
				</div>
			@endif
		</div>
		@if($order->status == 'Available' && ! Auth::User()->ni_admin)
		<div class="col-md-12">
		@else
		<div class="col-md-4">
		@endif
	@if(Auth::user()->ni_admin == 1)		
	 <div class="box box-success">
		<div class="box-header with-border">
                                        <h4><i class="fa fa-user-circle-o"></i>
                                                Previous Writer
                                        </h4>
                                        <div class="box-tools pull-right">
                                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                                        </div>
                                </div>
                                @if(!$order->prev_writer)
                                <div class="box-body">
                                        This client has not had a previous order on this subject
                                </div>
				@else
				<div class="box-body">
                                        <div class="col-md-4">
                                                Name:
                                        </div>
                                        <div class="col-md-8">
                                                <b><a href="/writer/{{$order->prev_writer}}">
                                                {{User::findorfail($order->prev_writer)->first_name}} {{User::find($order->prev_writer)->last_name}}
                                                </a></b>
                                        </div>
                                     	<div class="col-md-4">
						Rating:
					</div>
                                      <div class="col-md-8">
                                              <span class="text-green pull-left">
			                    	<b>{{$order->prev_order_rating}}</b><i class="fa fa-star"></i>
			                    </span>
                                        </div>
                                       
                                     
                                      
                                       
                                        <div class="col-md-4">
                                                Phone:
                                        </div>
                                        <div class="col-md-8">
                                                <b>{{User::findorfail($order->prev_writer)->phone1}}</b>
                                        </div>
					@if($order->prev_order_comments)
					<div class="direct-chat-messages direct-chat-primary" style="height:100%;">
						<div class="direct-chat-msg">
                                                	<div class="direct-chat-text" style="margin-left:0px;">
                                                		{!!$order->prev_order_comments!!}
                                                	</div>
                                                </div>
					</div>
					@endif

				
						
                                </div>
				@endif
</div>
	@endif
			@if(Auth::user()->ni_admin == 1 || Auth::user()->id == $order->user_id)
			<div class="box box-success">
				<div class="box-header with-border">
					<h4><i class="fa fa-user-plus"></i>
						Writer Assigned
					</h4>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                		</button>
					</div>
				</div>
				@if($order->status == "Available" || $order->status == "Not-Available" || $order->status=="Unpaid")
				<div class="box-body">
					No Writer assigned Yet
				</div>
				@else
				<div class="box-body">
					<div class="col-md-4">
						Name:
					</div>
					<div class="col-md-8">
						<b><a href="/writer/{{$order->user_id}}">
						{{User::findorfail($order->user_id)->first_name}} {{User::find($order->user_id)->last_name}}
						</a></b>
					</div>
					<div class="col-md-4">
						Email:
					</div>
					<div class="col-md-8">
						<b>{{User::findorfail($order->user_id)->email}}</b>
					</div>
					<div class="col-md-4">
						Phone:
					</div>
					<div class="col-md-8">
						<b>{{User::findorfail($order->user_id)->phone1}}</b>
					</div>
				</div>
				@endif
			</div>
			@endif
			@if($order->user_id == Auth::User()->id)
				<div class="box box-danger box-solid"  id="fines">
					<div class="box-header with-border">
						<h4><i class="icon fa fa-warning"></i>
							Order Fines!
						</h4>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
	                		</button>
						</div>
					</div>
					<div class="box-body">
						@foreach($order->fines as $fine)
						<div class="callout callout-danger">
		                	<h5>Fine #{{$fine->id}}</h5>
		                	<strong>Reason for fine</strong><br>
					        {{$fine->reason}}<br>
							<strong>Total Fee fined</strong><br>
					        -${{$fine->total_fine}}<br>
							<strong>Writer Fined</strong><br>
					        {{$fine->user->first_name}}
			            </div>
						@endforeach
					</div>
				</div>
			@endif
			@if(Auth::user()->ni_admin ==1 && $order->approved !== 1)
			<div class="box box-success">
				<div class="box-header with-border">
					<h4><i class="fa fa-pencil-square-o"></i>
						Update Order Status
					</h4>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                		</button>
					</div>
				</div>
				<div class="box-body">
					<form method="post" action="/assign/{{$order->id}}">
						{{method_field('PATCH')}}
					<input type="hidden" name="order_id" value="{{$order->id}}">
					<input type="hidden" name="user_id" value="{{Auth::user()->id }}">
					<div class="form-group col-md-12">
						{{csrf_field()}}
						<label>Compensation</label> <br>
						<small>The total amount to be paid to the writer</small>
						<input type="number" name="compensation" class="form-control"
						placeholder="Enter amount here..."
						Required="required"
						value="{{$order->compensation}}">
					</div>
					<div class="form-group col-md-12">
						<label>Select Order Status</label>
						<select type="text" name="status" class="form-control" >
							<option value="Active" selected="selected">Active</option>
							<option value="Active-Revision">Active-Revision</option>
							<option value="Available">Available</option>
						</select>
					</div>
					<div class="form-group col-md-12">
						<label>Select Writer</label>
						<select name="writer" class="form-control" Required="required" placeholder="Assign order to a particular writer">
							@foreach($writers as $writer)
							<option value="{{$writer->id}}"
								@if($writer->id == $order->user_id)
								Selected="selected"
								@endif
								>{{$writer->first_name}} {{$writer->last_name}}</option>
							@endforeach
						</select>					</div>
						<button class="btn btn-success" type="submit" >Update
	                  	</button>
	                  	</form>
					</div>
			</div>
			<!-- Add more time/hours  -->

			<div class="box box-success" id="moretime">
				<div class="box-header with-border">
					<h4><i class="fa fa-pencil"></i>
						Update order deadline
					</h4>
					<div class="box-tools pull-right">

						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                		</button>
					</div>
				</div>
				{{ Form::open(array('url'=>'add-hours')) }}
				<input type="hidden" name="order_id" value="{{$order->id}}">
				<div class="box-body">
					<div class="form-group col-md-6">
	                		<label>Enter new Deadline:</label> <!-- <span>In hours</span> -->

			                <div class="input-group">
			                  <div class="input-group-addon">
			                    <i class="fa fa-calendar-plus-o"></i>
			                  </div>
			                  <input type="datetime-local" name="deadline" id="deadline" class="form-control"
				                  	value="{{$order->deadline}}" Required="Required">
			                </div>
		                <!-- /.input group -->
	              		</div>
				</div>
				<div class="box-footer">
					{{ Form::submit('Update Order deadline',array('class'=>'btn btn-success')) }}
				</div>

				  {{ Form::close() }}
			</div>


			@elseif($order->is_complete == 1 && $order->approved == 1)
			<div class="box box-success">
				<div class="box-body">
					<div class="callout callout-info">
					{{$order->order_no}} is Completed and Approved for payment.
					</div>
				</div>
			</div>

			@endif
			@if(Auth::user()->ni_admin == 1)
			<div class="box box-success">
				<div class="box-header with-border">
					<h4><i class="fa fa-pencil-square-o"></i>
						Update/Delete Order
					</h4>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                		</button>
					</div>
				</div>
				<div class="box-body">

					<a class="btn btn-success" href="/orders/{{$order->id}}/edit" >Edit/Update
                      </a>
                      <a class="btn btn-danger pull-right" href="/orders/delete/{{$order->id}}">
							Delete Order
						</a>
				</div>
			</div>
			@endif


			@if(Auth::user()->ni_admin==1 || $order->user_id == Auth::user()->id )
			<!-- Deliver order widget -->
			<div class="box box-success">
				<div class="box-header with-border">
					<h4><i class="fa fa-upload"></i>
					<!-- Added a deliver or update order text -->
						Deliver/Update Order
					</h4>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                		</button>
					</div>
				</div>
				<div class="box-body">

					{{ Form::open(array('url'=>'deliver-order','files'=>true)) }}
					<input type="hidden" name="order_id" value="{{$order->id}}">
					<input type="hidden" name="user_id" value="{{Auth::user()->id}}">
					<div class="form-group">
						{{ Form::label('is_complete','Select the type of delivery') }}
						{{Form::select('is_complete',array('1' => 'Final Product', '0' => 'Draft Paper',),null,array('class'=>'form-control'))}}
					</div>
					<div class="form-group">
					  {{ Form::label('file','Select File to Upload') }}
					  {{ Form::file('file',array('id'=>'','class'=>'form-control','required'=>'required')) }}
					  @if ($errors->has('file'))
				            <span class="help-block">
				                <strong>{{ $errors->first('file') }}</strong>
				            </span>
			        	@endif
				  	</div>
				  <!-- submit buttons -->
				  <div class="form-group">
				  <!-- reset buttons -->
				  {{ Form::reset('Reset',array('class'=>'btn btn-info')) }}

				  {{ Form::submit('Deliver Order',array('class'=>'btn btn-success')) }}

				  {{ Form::close() }}
				  </div>


				</div>
				@if (count($order->order_delivery_reports)>0)
		        <div class="box-footer" id="order_file">
		          <h4>Uploaded papers</h4>
		         @foreach($order->order_delivery_reports as $order_delivery_report)

		            @if($order_delivery_report->is_complete == 1)
		            <a class="btn btn-default btn-file" href="{{route('get_file', $order_delivery_report->file_name)}}">
		                  <i class="fa fa-paperclip"></i>
		                  Final Product copy {{$order_delivery_report->created_at->format('F j, Y H:i A')}}
		                </a>
		                @elseif($order_delivery_report->is_complete == 0)
		                <a class="btn btn-default btn-file" href="{{route('get_file', $order_delivery_report->file_name)}}">
		                  <i class="fa fa-paperclip"></i>
		                  Draft copy {{$order_delivery_report->created_at->format('F j, Y H:i A')}}
		              </a>
		            @endif
		          @endforeach

		          @if(Auth::user()->ni_admin && $order->approved !==1 )
		          {{ Form::open(array('url'=>'approve-order')) }}
				  <input type="hidden" name="order_id" value="{{$order->id}}">
				  <label>Enter Bonus if any</label>
				  <input type="integer" name="bonus" class="form-control" placeholder="Enter 0 if none"
            				Required="required">

				  <div class="form-group">
		          {{Form::submit('Approve this work as complete',array('class'=> ' btn btn-success'))}}
		          </div>
		        </div>
        		{{ Form::close() }}

        		@endif

					@endif
			</div>
			@endif
			<!-- Order fines widget -->
			@if(Auth::user()->ni_admin )
			<div class="box box-danger">
				<div class="box-header with-border">
					<h4><i class="fa fa-warning"></i>
						Apply a fine on this order
					</h4>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                		</button>
					</div>
				</div>
				@if(Auth::user()->ni_admin)
				<div class="box-body">
        			{{Form::open(array('url'=>'apply-fines'))}}
        			{{Form::label('name', 'Select a writer to appy this fine on')}}
        			<input type="hidden" name="order_id" value="{{$order->id}}">
        			<div class="form-group">
        				<select name="writer" class="form-control" Required="required">
							@foreach($writers as $writer)
							<option value="{{$writer->id}}"
								@if($writer->id == $order->user_id)
								Selected="selected"
								@endif
								>{{$writer->first_name}} {{$writer->last_name}}</option>
							@endforeach
						</select>
        			</div>
        			<div class="form-group">
        			{{Form::number('total_fine', null, array('class'=>'form-control','requried'=>'required', 'placeholder'=>'Enter the Fine amount here'))}}
        			</div>
        			<div class="form-group">
        			{{Form::text('reason', null, array('class'=>'form-control','requried'=>'required', 'placeholder'=>'Give a reason for the fine'))}}
        			</div>
        			<div class="form-group">
        			{{ Form::submit('Apply Fine',array('class'=>'btn btn-success')) }}
        			</div>
        			{{Form::close()}}
        		</div>
        		@endif
			</div>
			@endif
		</div>
	</div>
</section>

@stop
