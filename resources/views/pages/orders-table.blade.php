@extends('layout')

@section('body')
<?php use Carbon\Carbon; 
?>


<section class="content">
	<div class="row">
		<div class="col-md-12">
			@if(count(Session('delete_message')))
				
              <div class="alert alert-info alert-dismissible">
              	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                 <? $delete_message = Session('delete_message'); ?>
                {{$delete_message}}x
              </div>
              @elseif(Session::has('message'))
              <div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                <h4><i class="icon fa fa-info"></i>Success!</h4>
                 <? $message = Session('message'); ?>
                {{$message}}
              </div>
              @elseif(Session::has('warning'))
              <div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                <h4><i class="icon fa fa-warning"></i>Alert!</h4>
                 <? $warning = Session('warning'); ?>
                {{$warning}}
              </div>
              @endif
              
              @if(Auth::user()->ni_admin)
              <div class="box box-success">
	              	<div class="box-header with-border">
	              		<h3 class="box-title">Order Reports</h3>
	              		<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
	                		</button>
						</div>
	              	</div>

	              	<div class="box-body">
	              		<div class="col-lg-4 col-xs-6">
	          				<!-- small box -->
				          	<div class="small-box bg-green">
					            <div class="inner">
					              <h3>{{$no_approved}}</h3>

					              <p>Orders Approved</p>
					            </div>
					            <div class="icon">
					              <i class="fa fa-file-text-o"></i>
					            </div>
					            <span href="#" class="small-box-footer">
					              Completed and Approved this month
					            </span>
				            </div>
	        			</div>
				          <div class="col-lg-4 col-xs-6">
				          		<!-- small box -->
					          <div class="small-box bg-green">
					            <div class="inner">
					              <h3>{{$no_available}}</h3>

					              <p>Orders pending</p>
					            </div>
					            <div class="icon">
					              <i class="fa fa-file-text-o"></i>
					            </div>
					            <span href="#" class="small-box-footer">
					              All Orders Pending (Not awarded)
					            </span>
					          </div>
				        </div>
			      		<div class="col-lg-4 col-xs-6">
			          		<!-- small box -->
				          <div class="small-box bg-green">
					            <div class="inner">
					              <h3>{{$no_active}}</h3>
									<p>Orders Active</p>
					            </div>
					            <div class="icon">
					              <i class="fa fa-file-text-o"></i>
					            </div>
					            <span href="#" class="small-box-footer">
					              Orders being processed
					            </span>
				          </div>
			        	</div>
	      				<div class="col-lg-4 col-xs-6">
				          <!-- small box -->
					          <div class="small-box bg-green">
						            <div class="inner">
						              <h3>{{$no_delivered}}</h3>
										<p>Orders Delivered</p>
						            </div>
						            <div class="icon">
						              <i class="fa fa-file-text-o"></i>
						            </div>
						            <span href="#" class="small-box-footer">
						              Orders Delivered this month
						            </span>
					          </div>
			        	</div>
	      				<div class="col-lg-4 col-xs-6">
				          <!-- small box -->
					          <div class="small-box bg-red">
						            <div class="inner">
						              <h3>{{$no_late}}</h3>
										<p>Late Orders</p>
						            </div>
						            <div class="icon">
						              <i class="fa fa-file-text-o"></i>
						            </div>
						            <span href="#" class="small-box-footer">
						              Late Orders this month
						            </span>
					          </div>
			        	</div>
	          		</div>
          	  </div>
              @endif
              
				 <!-- Custom Tabs (Pulled to the right) -->
          <div class="nav-tabs-custom success">
	            <ul class="nav nav-tabs">
	              <li 
	              @if(count($re_orders) >0)
	              class = "active"
	              @endif
	               style="font-size:20px"><a href="#revision" data-toggle="tab">Revision</a></li>
	              <li 
	              @if(!count($re_orders) > 0)
	              class = "active"
	              @endif
	              style="font-size:20px"><a href="#activeorders" data-toggle="tab">Active</a></li>
	              <li style="font-size:20px"><a href="#pending" data-toggle="tab">Available</a></li>
	              <li style="font-size:20px"><a href="#delivered" data-toggle="tab">Delivered</a></li>
	              <li style="font-size:20px"><a href="#approved" data-toggle="tab">Approved</a></li>
	              <li style="font-size:20px"><a href="#late" data-toggle="tab">Late</a></li>
	              <li class="pull-right header" style="font-size:25px">Orders <i class="fa fa-th"></i></li>
	            </ul>
	            <div class="tab-content">
	              <div 
	              @if(count($re_orders) !=0)
	              class = "tab-pane active"
	              @else
	              class ="tab-pane"
	              @endif 
	              id="revision">
		                <div class="box-group" id="all_orders">
	                          
	                            <!-- Start iterating through the order data from the orderscontroller -->
	                            @if(count($re_orders)!=0)
	                            <h3><small class="box-title">Orders in Revision</small></h3>
	                            @foreach($re_orders as $order)
	                            <?php   
	                                $deadline = Carbon::parse("$order->deadline");
	                                //$time_now =Carbon::now();
	                                $timer= $deadline->diffForHumans()
	                           ?>
	                          <div class="panel box box-success">
	                                <div class="box-header with-border">
	                                    <a data-toggle="collapse" data-parent="#all_orders" href="#{{$order->id}}">
	                                          <h4 class="box-title">{{$order->order_no}}</h4>
	                                          <span class="label label-success pull-right">${{$order->compensation}}</span>
	                                    </a>  
	                                </div>
	                                <div id="{{$order->id}}" class="panel-collapse collapse">
	                                  <div class="box-body">
	                                    
	                                        @if(Auth::user()->ni_admin)
	                                        <div class="col-md-5 col-sm-6"><strong>Writer:</strong></div>
	                                        <div class="col-md-7 col-sm-6"><h4>{{$order->user->first_name}}</h4></div>

	                                        @endif
	                                        <div class="col-md-5 col-sm-6"><strong>Order Type:</strong></div>
	                                        <div class="col-md-7 col-sm-6">{{$order->type_of_product}}</div>
	                                        <div class="col-md-5 col-sm-6"><strong>Order Status:</strong></div>
	                                        <div class="col-md-7 col-sm-6"><span class="btn btn-sm btn-primary btn-flat ">{{$order->status}}</span></div>
	                                        <div class="col-md-5 col-sm-6"><strong>Topic:</strong></div>
	                                        <div class="col-md-7 col-sm-6">{{$order->subject}}</div>
	                                        <div class="col-md-5 col-sm-6"><strong>Pages:</strong><small>(275 Words/Page)</small></div>
	                                        <div class="col-md-7 col-sm-6">{{$order->word_length}}, <strong>{{$order->spacing}} Spaced</strong></div>
	                                        <div class="col-md-5 col-sm-6"><strong>Academic Level:</strong></div>
	                                        <div class="col-md-7 col-sm-6">{{$order->academic_level}}</div>
	                                        <div class="col-md-5 col-sm-6"><strong>Deadline:</strong></div>
	                                        <div class="col-md-7 col-sm-6">{{$timer}}</div>
	                                        <div class="col-md-5 col-sm-6"><strong>Reference Style:</strong></div>
	                                        <div class="col-md-7 col-sm-6">{{$order->style}}</div>
	                                        <div class="col-md-5 col-sm-6"><strong>Number of Sources:</strong></div>
	                                        <div class="col-md-7 col-sm-6">{{$order->no_of_sources}}</div>
	                                        <div class="col-md-5 col-sm-6"><strong>Order Total:</strong></div>
	                                        <div class="col-md-7 col-sm-6 text-green">${{$order->compensation}}</div>
	                                    
	                                    
	                                        <div class="box-footer clearfix">
	                                          <a class="btn btn-sm btn-success btn-flat pull-left" href="orders/{{$order->id}}" >View Order</a>
	                                        </div>
	                                  </div>
	                                </div>    
	                          </div>
	                            @endforeach


	                          <div class="box-footer clearfix">
	                              {!! $re_orders->fragment('revision')->render() !!}
	                          </div>
	                           @else
	                                No Data to display, You don't have any orders that need revision
	                                @endif
	                  	</div>
	              </div>
	              <!-- /.tab-pane -->
	              <div 
	              @if(count($re_orders) == 0)
	              class = "tab-pane active"
	              @else
	              class ="tab-pane"
	              @endif 
	              id="activeorders">
		                <div class="box-group" id="all_orders">
	                            <!-- Start iterating through the order data from the orderscontroller -->
	                            @if(count($act_orders)!=0)
	                          <h3><small>Active Orders</small></h3>
	                            @foreach($act_orders as $order)
	                            <?php   
	                                $deadline = Carbon::parse("$order->deadline");
	                                //$time_now =Carbon::now();
	                                $timer= $deadline->diffForHumans()
	                           ?>
	                          <div class="panel box box-success">
	                                <div class="box-header with-border">
	                                    <a data-toggle="collapse" data-parent="#all_orders" href="#{{$order->id}}">
	                                          <h4 class="box-title">{{$order->order_no}}</h4>
	                                          <span class="label label-success pull-right">${{$order->compensation}}</span>
	                                    </a>  
	                                </div>
	                                <div id="{{$order->id}}" class="panel-collapse collapse">
	                                  <div class="box-body">
	                                    
	                                        
	                                  		@if(Auth::user()->ni_admin)
	                                        <div class="col-md-5 col-sm-6"><strong>Writer:</strong></div>
	                                        <div class="col-md-7 col-sm-6"><h4>{{$order->user->first_name}}</h4></div>

	                                        @endif
	                                        <div class="col-md-5 col-sm-6"><strong>Order Type:</strong></div>
	                                        <div class="col-md-7 col-sm-6">{{$order->type_of_product}}</div>
	                                        <div class="col-md-5 col-sm-6"><strong>Order Status:</strong></div>
	                                        <div class="col-md-7 col-sm-6"><span class="btn btn-sm btn-primary btn-flat ">{{$order->status}}</span></div>
	                                        <div class="col-md-5 col-sm-6"><strong>Topic:</strong></div>
	                                        <div class="col-md-7 col-sm-6">{{$order->subject}}</div>
	                                        <div class="col-md-5 col-sm-6"><strong>Pages:</strong><small>(275 Words/Page)</small></div>
	                                        <div class="col-md-7 col-sm-6">{{$order->word_length}}, <strong>{{$order->spacing}} Spaced</strong></div>
	                                        <div class="col-md-5 col-sm-6"><strong>Academic Level:</strong></div>
	                                        <div class="col-md-7 col-sm-6">{{$order->academic_level}}</div>
	                                        <div class="col-md-5 col-sm-6"><strong>Deadline:</strong></div>
	                                        <div class="col-md-7 col-sm-6">{{$timer}}</div>
	                                        <div class="col-md-5 col-sm-6"><strong>Reference Style:</strong></div>
	                                        <div class="col-md-7 col-sm-6">{{$order->style}}</div>
	                                        <div class="col-md-5 col-sm-6"><strong>Number of Sources:</strong></div>
	                                        <div class="col-md-7 col-sm-6">{{$order->no_of_sources}}</div>
	                                        <div class="col-md-5 col-sm-6"><strong>Order Total:</strong></div>
	                                        <div class="col-md-7 col-sm-6 text-green">${{$order->compensation}}</div>
	                                    
	                                    
	                                        <div class="box-footer clearfix">
	                                          <a class="btn btn-sm btn-success btn-flat pull-left" href="orders/{{$order->id}}" >View Order</a>
	                                        </div>
	                                  </div>
	                                </div>    
	                          </div>
	                            @endforeach


	                          <div class="box-footer clearfix">
	                              
	                          </div>
	                           @else
	                                No Data to display, You don't have any active orders yet. <a href="/orders#pending"><span class="btn btn-sm btn-primary btn-flat">Place a bid</span></a> to be assigned an order
	                                @endif
	                  	</div>
	              </div>
	              <!-- /.tab-pane -->
	              <div class="tab-pane" id="pending">
		                
		                <div class="box-group" id="all_orders">
	                            <!-- Start iterating through the order data from the orderscontroller -->
	                            @if(count($av_orders)!=0)
	                          <h3><small>Available Orders</small></h3>
	                            @foreach($av_orders as $order)
	                            <?php   
	                                $deadline = Carbon::parse("$order->deadline");
	                                //$time_now =Carbon::now();
	                                $timer= $deadline->diffForHumans()
	                           ?>
	                          <div class="panel box box-success">
	                                <div class="box-header with-border">
	                                    <a data-toggle="collapse" data-parent="#all_orders" href="#{{$order->id}}">
	                                          <h4 class="box-title">{{$order->order_no}}</h4>
	                                          <span class="label label-success pull-right">${{$order->compensation}}</span>
	                                    </a>  
	                                </div>
	                                <div id="{{$order->id}}" class="panel-collapse collapse">
	                                  <div class="box-body">
	                                    
	                                        <div class="col-md-5 col-sm-6"><strong>Order Type:</strong></div>
	                                        <div class="col-md-7 col-sm-6">{{$order->type_of_product}}</div>
	                                        <div class="col-md-5 col-sm-6"><strong>Order Status:</strong></div>
	                                        <div class="col-md-7 col-sm-6"><span class="btn btn-sm btn-primary btn-flat ">{{$order->status}}</span></div>
	                                        <div class="col-md-5 col-sm-6"><strong>Topic:</strong></div>
	                                        <div class="col-md-7 col-sm-6">{{$order->subject}}</div>
	                                        <div class="col-md-5 col-sm-6"><strong>Pages:</strong><small>(275 Words/Page)</small></div>
	                                        <div class="col-md-7 col-sm-6">{{$order->word_length}}, <strong>{{$order->spacing}} Spaced</strong></div>
	                                        <div class="col-md-5 col-sm-6"><strong>Academic Level:</strong></div>
	                                        <div class="col-md-7 col-sm-6">{{$order->academic_level}}</div>
	                                        <div class="col-md-5 col-sm-6"><strong>Deadline:</strong></div>
	                                        <div class="col-md-7 col-sm-6">{{$timer}}</div>
	                                        <div class="col-md-5 col-sm-6"><strong>Reference Style:</strong></div>
	                                        <div class="col-md-7 col-sm-6">{{$order->style}}</div>
	                                        <div class="col-md-5 col-sm-6"><strong>Number of Sources:</strong></div>
	                                        <div class="col-md-7 col-sm-6">{{$order->no_of_sources}}</div>
	                                        <div class="col-md-5 col-sm-6"><strong>Order Total:</strong></div>
	                                        <div class="col-md-7 col-sm-6 text-green">${{$order->compensation}}</div>
	                                    
	                                    
	                                        <div class="box-footer clearfix">
	                                          <a class="btn btn-sm btn-success btn-flat pull-left" href="orders/{{$order->id}}" >View Order</a>
	                                          <a class="btn btn-sm btn-success btn-flat pull-right" href="orders/{{$order->id}}#bidonthis" >Place Bid</a>
	                                        </div>
	                                  </div>
	                                </div>    
	                          </div>
	                            @endforeach


	                          <div class="box-footer clearfix">
	                              {!! $av_orders->fragment('pending')->render() !!}
	                          </div>
	                           @else
	                                No Data to display, There are no orders available
	                                @endif
	                  	</div>
	              </div>
	              <!-- /.tab-pane -->
	              <div class="tab-pane" id="delivered">
		               <div class="box-group" id="all_orders">
	                            <!-- Start iterating through the order data from the orderscontroller -->
	                            @if(count($del_orders)!=0)
	                            <h3><span class="small">Orders Delivered</span></h3>
	                            @foreach($del_orders as $order)
	                            <?php   
	                                $deadline = Carbon::parse("$order->deadline");
	                                //$time_now =Carbon::now();
	                                $timer= $deadline->diffForHumans()
	                           ?>
	                          <div class="panel box box-success">
	                                <div class="box-header with-border">
	                                    <a data-toggle="collapse" data-parent="#all_orders" href="#{{$order->id}}">
	                                          <h4 class="box-title">{{$order->order_no}}</h4>
	                                          <span class="label label-success pull-right">${{$order->compensation}}</span>
	                                    </a>  
	                                </div>
	                                <div id="{{$order->id}}" class="panel-collapse collapse">
	                                  <div class="box-body">
	                                    	
	                                        @if(Auth::user()->ni_admin)
	                                        <div class="col-md-5 col-sm-6"><strong>Writer:</strong></div>
	                                        <div class="col-md-7 col-sm-6"><h4>{{$order->user->first_name}}</h4></div>

	                                        @endif
	                                        <div class="col-md-5 col-sm-6"><strong>Order Type:</strong></div>
	                                        <div class="col-md-7 col-sm-6">{{$order->type_of_product}}</div>
	                                        <div class="col-md-5 col-sm-6"><strong>Order Status:</strong></div>
	                                        <div class="col-md-7 col-sm-6"><span class="btn btn-sm btn-primary btn-flat ">{{$order->status}}</span></div>
	                                        <div class="col-md-5 col-sm-6"><strong>Topic:</strong></div>
	                                        <div class="col-md-7 col-sm-6">{{$order->subject}}</div>
	                                        <div class="col-md-5 col-sm-6"><strong>Pages:</strong><small>(275 Words/Page)</small></div>
	                                        <div class="col-md-7 col-sm-6">{{$order->word_length}}, <strong>{{$order->spacing}} Spaced</strong></div>
	                                        <div class="col-md-5 col-sm-6"><strong>Created on:</strong></div>
	                                        <div class="col-md-7 col-sm-6">{{$order -> created_at->format('F j, Y')}}</div>
	                                        <div class="col-md-5 col-sm-6"><strong>Delivered on:</strong></div>
	                                        <div class="col-md-7 col-sm-6">{{$order->order_delivery_reports->last()->created_at->format('F j, Y h:i A')}}</div>
	                                        <div class="col-md-5 col-sm-6"><strong>Deadline:</strong></div>
	                                        <div class="col-md-7 col-sm-6">{{$timer}}</div>
	                                        <div class="col-md-5 col-sm-6"><strong>Order Total:</strong></div>
	                                        <div class="col-md-7 col-sm-6 text-green">${{$order->compensation}}</div>
	                                    
	                                    
	                                        <div class="box-footer clearfix">
	                                          <a class="btn btn-sm btn-success btn-flat pull-left" href="orders/{{$order->id}}" >View Order</a>
	                                        </div>
	                                  </div>
	                                </div>    
	                          </div>
	                            @endforeach


	                          <div class="box-footer clearfix">
	                              {!! $del_orders->fragment('delivered')->render() !!}
	                          </div>
	                           @else
	                                No Data to display, Sorry you haven't delivered any orders yet
	                                @endif
	                  	</div>
	              </div>
					<!-- /.tab-pane -->
	              <div class="tab-pane" id="approved">
		               <div class="box-group" id="all_orders">
	                            <!-- Start iterating through the order data from the orderscontroller -->
	                            @if(count($ap_orders)!=0)
	                            <h3><span class="small">Orders Approved</span></h3>
	                            @foreach($ap_orders as $earning)
	                            <div class="panel box box-success">
	                                <div class="box-header with-border">
	                                    <a data-toggle="collapse" data-parent="#all_orders" href="#{{$earning->id}}">
	                                          <h4 class="box-title">
	                                          	@if(count($earning->order) >0)
	                                          	{{$earning->order->order_no}}
	                                          	@else
	                                          	No Order Found
	                                          	<small>Was it deleted?</small>
	                                          	@endif
	                                          </h4>
	                                          <span class="label label-success pull-right">${{$earning->total}}</span>
	                                    </a>  
	                                </div>
	                                <div id="{{$earning->id}}" class="panel-collapse collapse">
	                                  <div class="box-body">
	                                    	
	                                        <div class="col-md-5 col-sm-6"><strong>Writer:</strong></div>
	                                        <div class="col-md-7 col-sm-6"><h4>{{$earning->user->first_name}}<h4></div>
	                                        
	                                        @if(count($earning->order) >0)
	                                        <div class="col-md-5 col-sm-6"><strong>Order Type:</strong></div>
	                                        <div class="col-md-7 col-sm-6">{{$earning->order->type_of_product}}</div>
	                                        <div class="col-md-5 col-sm-6"><strong>Topic:</strong></div>
	                                        <div class="col-md-7 col-sm-6">{{$earning->order->subject}}</div>
	                                        <div class="col-md-5 col-sm-6"><strong>Pages:</strong><small>(275 Words/Page)</small></div>
	                                        <div class="col-md-7 col-sm-6">{{$earning->order->word_length}}, <strong>{{$earning->order->spacing}} Spaced</strong></div>
	                                        <div class="col-md-5 col-sm-6"><strong>Created on:</strong></div>
	                                        <div class="col-md-7 col-sm-6">{{$earning->order -> created_at->format('F j, Y')}}</div>
	                                        <div class="col-md-5 col-sm-6"><strong>Delivered on:</strong></div>
	                                        <div class="col-md-7 col-sm-6">{{$earning->order->order_delivery_reports->last()->created_at->format('F j, Y h:i A')}}</div>
	                                        @else
	                                        <div class="col-sm-12">
	                                        	Order ID :{{$earning->order_id}} was not found <small>Was it deleted?</small>
	                                        </div>
	                                        @endif

	                                        <div class="col-md-5 col-sm-6"><strong>Approved on:</strong></div>
	                                        <div class="col-md-7 col-sm-6">{{$earning->created_at->format('F j, Y h:i A')}}</div>
	                                        @if(count($earning->order) >0)
	                                        <div class="col-md-5 col-sm-6"><strong>Compensation:</strong></div>
	                                        <div class="col-md-7 col-sm-6 text-green">${{$earning->order->compensation}}</div>
	                                        @endif
	                                        <div class="col-md-5 col-sm-6"><strong>Bonus:</strong></div>
	                                        <div class="col-md-7 col-sm-6 text-green">${{$earning->bonus}}</div>
	                                        <div class="col-md-5 col-sm-6"><strong>Fine:</strong></div>
	                                        <div class="col-md-7 col-sm-6 text-green">${{$earning->late_fee}}</div>
	                                        <div class="col-md-5 col-sm-6"><strong>Approved Total:</strong></div>
	                                        <div class="col-md-7 col-sm-6 text-green">${{$earning->total}}</div>
	                                    
	                                    
	                                        <div class="box-footer clearfix">
	                                        	@if(count($earning->order) >0)
	                                          <a class="btn btn-sm btn-success btn-flat pull-left" href="/orders/{{$earning->order->id}}" >View Order</a>
	                                          @else
	                                          Order ID :{{$earning->order_id}} was not found <small>Was it deleted?</small>
	                                          @endif
	                                        </div>
	                                  </div>
	                                </div>    
	                          	</div>
	                          	
	                            @endforeach


	                          <div class="box-footer clearfix">
	                              {!! $ap_orders->fragment('approved')->render() !!}
	                          </div>
	                           @else
	                                No Data to display, Sorry you haven't had any Approved orders yet
	                           @endif
	                  	</div>
		                
	              </div>
	              <!-- /.tab-pane -->
					<!-- /.tab-pane -->
	              <div class="tab-pane" id="late">
		                <div class="box-group" id="all_orders">
	                            <!-- Start iterating through the order data from the orderscontroller -->
	                            @if(count($lt_orders)!=0)
	                          <h3><small>Late Orders</small></h3>
	                            @foreach($lt_orders as $order)
	                            <?php   
	                                $deadline = Carbon::parse("$order->deadline");
	                                //$time_now =Carbon::now();
	                                $timer= $deadline->diffForHumans()
	                           ?>
	                          <div class="panel box box-success">
	                                <div class="box-header with-border">
	                                    <a data-toggle="collapse" data-parent="#all_orders" href="#app_{{$order->id}}">
	                                          <h4 class="box-title">{{$order->order_no}}</h4>
	                                          <span class="label label-success pull-right">${{$order->compensation}}</span>
	                                    </a>  
	                                </div>
	                                <div id="app_{{$order->id}}" class="panel-collapse collapse">
	                                  <div class="box-body">
	                                    
	                                        @if(Auth::user()->ni_admin)
	                                        <div class="col-md-5 col-sm-6"><strong>Writer:</strong></div>
	                                        <div class="col-md-7 col-sm-6"><h4>{{$order->user->first_name}}</h4></div>

	                                        @endif
	                                        <div class="col-md-5 col-sm-6"><strong>Order Type:</strong></div>
	                                        <div class="col-md-7 col-sm-6">{{$order->type_of_product}}</div>
	                                        <div class="col-md-5 col-sm-6"><strong>Order Status:</strong></div>
	                                        <div class="col-md-7 col-sm-6"><span class="btn btn-sm btn-primary btn-flat ">{{$order->status}}</span></div>
	                                        <div class="col-md-5 col-sm-6"><strong>Topic:</strong></div>
	                                        <div class="col-md-7 col-sm-6">{{$order->subject}}</div>
	                                        <div class="col-md-5 col-sm-6"><strong>Pages:</strong><small>(275 Words/Page)</small></div>
	                                        <div class="col-md-7 col-sm-6">{{$order->word_length}}, <strong>{{$order->spacing}} Spaced</strong></div>
	                                        <div class="col-md-5 col-sm-6"><strong>Academic Level:</strong></div>
	                                        <div class="col-md-7 col-sm-6">{{$order->academic_level}}</div>
	                                        <div class="col-md-5 col-sm-6"><strong>Deadline:</strong></div>
	                                        <div class="col-md-7 col-sm-6">{{$timer}}</div>
	                                        <div class="col-md-5 col-sm-6"><strong>Reference Style:</strong></div>
	                                        <div class="col-md-7 col-sm-6">{{$order->style}}</div>
	                                        <div class="col-md-5 col-sm-6"><strong>Number of Sources:</strong></div>
	                                        <div class="col-md-7 col-sm-6">{{$order->no_of_sources}}</div>
	                                        <div class="col-md-5 col-sm-6"><strong>Order Total:</strong></div>
	                                        <div class="col-md-7 col-sm-6 text-green">${{$order->compensation}}</div>
	                                    
	                                    
	                                        <div class="box-footer clearfix">
	                                          <a class="btn btn-sm btn-success btn-flat pull-left" href="orders/{{$order->id}}" >View Order</a>
	                                        </div>
	                                  </div>
	                                </div>    
	                          </div>
	                            @endforeach


	                          <div class="box-footer clearfix">
	                              {!! $lt_orders->fragment('late')->render() !!}
	                          </div>
	                           @else
	                                No Data to display, Good stuff there are not late orders
	                                @endif
	                  	</div>
	              </div>
	              <!-- /.tab-pane -->
	            </div>
	            <!-- /.tab-content -->
          </div>
          		<!-- nav-tabs-custom -->


		</div>
	</div>
</section>

@stop