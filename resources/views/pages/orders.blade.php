@extends('layout')

@section('body')
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box box-success">
				<div class="box-header with-border">
					<h3 class="box-title">All Orders</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                		</button>
					</div>
				</div>
				<!-- end of Box header -->
				<!-- Start of Box content -->
				<div class="box-body no-padding">
					<div class="row">
						<div class="col-md-12">
							<div class="box-group" id="all_orders">
								<!-- Start iterating through the order data from the orderscontroller -->
									@foreach($orders as $order)
								<div class="panel box box-primary">
                        			<div class="box-header with-border">
	                        			<a data-toggle="collapse" data-parent="#all_orders" href="#{{$order->id}}">
	                              			<h4 class="box-title">Order #{{$order->id}}</h4>
	                              			<span class="label label-success pull-right">${{$order->total}}</span>
	                            		</a>  
                        			</div>
                        			<div id="{{$order->id}}" class="panel-collapse collapse">
	                        			<div class="box-body">
	                        				
	                        					<div class="col-md-3 col-sm-6"><strong>Order Type:</strong></div>
					                            <div class="col-md-9 col-sm-6">{{$order->type_of_product}}</div>
					                            <div class="col-md-3 col-sm-6"><strong>Topic:</strong></div>
					                            <div class="col-md-9 col-sm-6">{{$order->subject}}</div>
					                            <div class="col-md-3 col-sm-6"><strong>Pages:</strong><small>(275 Words/Page)</small></div>
					                            <div class="col-md-9 col-sm-6">{{$order->word_length}}, <strong>{{$order->spacing}} Spaced</strong></div>
					                            <div class="col-md-3 col-sm-6"><strong>Academic Level:</strong></div>
					                            <div class="col-md-9 col-sm-6">{{$order->academic_level}}</div>
					                            <div class="col-md-3 col-sm-6"><strong>Deadline:</strong></div>
					                            <div class="col-md-9 col-sm-6">{{$order->deadline}}</div>
					                            <div class="col-md-3 col-sm-6"><strong>Reference Style:</strong></div>
					                            <div class="col-md-9 col-sm-6">{{$order->style}}</div>
					                            <div class="col-md-3 col-sm-6"><strong>Number of Sources:</strong></div>
					                            <div class="col-md-9 col-sm-6">{{$order->no_of_sources}}</div>
					                            <div class="col-md-3 col-sm-6"><strong>Order Total:</strong></div>
					                            <div class="col-md-9 col-sm-6 text-green">${{$order->compensation}}</div>
					                        
	                        					<!-- <div class="col-md-2 col-sm-12"><strong>Title:</strong></div>
					                            <div class="col-md-10 col-sm-12">{{$order->title}}</div>
					                            @if ($order->instructions)
					                            <div class="col-md-2 col-sm-6"><strong>Instructions:</strong></div>
					                            <div class="col-md-10 col-sm-12">{!!$order->instructions!!}</div>
					                            @endif
					                            @if ($order->essential_sources)
											    <div class="col-md-2 col-sm-6"><strong>Essential Sources:</strong></div>
					                            <div class="col-md-10 col-sm-12">{{$order->essential_sources}}</div>
											    @endif
											    @if ($order->suggested_sources)
					                            <div class="col-md-2 col-sm-6"><strong>Suggested Sources:</strong></div>
					                            <div class="col-md-10 col-sm-12">{{$order->suggested_sources}}</div>
					                            @endif -->
	                        				
				                            	<div class="box-footer clearfix">
					                            	<a class="btn btn-sm btn-success btn-flat pull-left" href="orders/{{$order->id}}" >View Order</a>
						                            <a class="btn btn-sm btn-success btn-flat pull-right" href="orders/{{$order->id}}" >Place Bid</a>
				                            	</div>
				                         </div>
			                    	</div>    
				                </div>
				                @endforeach
				                {!! $orders->render() !!}
	                      	</div>	
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

@stop