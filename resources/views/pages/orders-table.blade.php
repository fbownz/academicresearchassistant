@extends('layout')

@section('body')
<?php use Carbon\Carbon; 
use App\Order; 
use App\User;?>
<section class="content">
	<div class="row">
		<div class="col-md-12">
			@if(count(Session('delete_message')))
				
              <div class="alert alert-info alert-dismissible">
              	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                 <? $delete_message = Session('delete_message'); ?>
                {{$delete_message}}
              </div>
              @endif
              @if(Auth::user()->ni_admin == 1)
              <div class="box box-success">
              	<div class="box-header with-border">
              		<h3 class="box-title">Order Reports</h3>
              		<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                		</button>
					</div>
              	</div>

              	<div class="box-body">
              		<div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3>{{Order::where('created_at', '>=', Carbon::now()->startOfMonth())->where('approved', '=', 1)->count()}}</h3>

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
              		<div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3>{{Order::where('created_at', '>=', Carbon::now()->startOfMonth())->where('status', '=', "Available")->where('user_id','=',null)->count()}}</h3>

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
      		<div class="col-lg-3 col-xs-6">
          <!-- small box -->
	          <div class="small-box bg-green">
		            <div class="inner">
		              <h3>{{Order::where('created_at', '>=', Carbon::now()->startOfMonth())->where('status', '=', "Active")->where('user_id','>',1)->count()}}</h3>
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
          	</div>
          	
              </div>
              @endif
              <? 

              $orders = $orders->where('user_id',"'Auth::user()->id'")?>
			<div class="box box-success">
				<div class="box-header with-border">
					<h3 class="box-title">Active Orders</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                		</button>
					</div>
				</div>
				<!-- Start of Box content -->
				
				<div class="box-body table-responsive no-padding">
							<table class="table table-striped" id="all_orders_table">
								<thead>
									<tr>
										<th>Order ID</th>
										<th>Status</th>
										<th>Date Placed</th>
										<th>Total</th>
										<th>Deadline</th>
									</tr>
								</thead>
									<tbody>
										@foreach($orders as $order)
										<?php 
											
											$deadline = Carbon::parse("$order->deadline");
											?>
									<tr>
										<td style="font-size:1.2em"><a href="{{route('order.show',$order->id)}}">{{$order->order_no}}</a>
										@if($order->is_late == 1 && $order ->status !== 'Delivered')
											<span data-toggle="tooltip" class="badge bg-red" title="{{$order->order_no}} is Late">Late!</span>
											@endif</td>
										<td>{{$order ->status}}</td>
										<td>{{$order -> created_at}}
										</td>
										<td>
											<span class="label label-success">${{$order->compensation}}</span>
										</td>
										<td>{{$deadline->format('F j, Y H:i A')}}</td>
										
									</tr>
									@endforeach
			                	</tbody>
			                	<tfoot>
			                	</tfoot>	
		                    </table>		
				</div>
				
					<div class="box-footer clearfix">
					</div>
			</div>
			<? $orders = Order::all()->where('status','Available')?>
			<div class="box box-success">
				<div class="box-header with-border">
					<h3 class="box-title">Available Orders</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                		</button>
					</div>
				</div>
				<!-- Start of Box content -->
				
				<div class="box-body table-responsive no-padding">
							<table class="table table-striped" id="all_orders_table">
								<thead>
									<tr>
										<th>Order ID</th>
										<th>Status</th>
										<th>Date Placed</th>
										<th>Total</th>
										<th>Deadline</th>
									</tr>
								</thead>
									<tbody>
										@foreach($orders as $order)
										<?php 
											
											$deadline = Carbon::parse("$order->deadline");
											?>
									<tr>
										<td style="font-size:1.2em"><a href="{{route('order.show',$order->id)}}">{{$order->order_no}}</a>
										@if($order->is_late == 1 && $order ->status !== 'Delivered')
											<span data-toggle="tooltip" class="badge bg-red" title="{{$order->order_no}} is Late">Late!</span>
											@endif</td>
										<td>{{$order ->status}}</td>
										<td>{{$order -> created_at}}
										</td>
										<td>
											<span class="label label-success">${{$order->compensation}}</span>
										</td>
										<td>{{$deadline->format('F j, Y H:i A')}}</td>
										
									</tr>
									@endforeach
			                	</tbody>
			                	<tfoot>
			                	</tfoot>	
		                    </table>		
				</div>
				
					<div class="box-footer clearfix">
					</div>
			</div>
		</div>
	</div>
</section>

@stop