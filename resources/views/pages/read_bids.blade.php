@extends ('layout')

@section('body')

<?php 
  use Carbon\Carbon; 
  use App\User; 
?>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12 col-sd-12 ">
            <div class="box-group" id="all_bids">
              <h4 class="box-title">Recent Bids made</h4>
              @if(count($orders)>0)
              @foreach($orders as $order)
                
                
              <div class="panel box box-primary">
                              <div class="box-header with-border">
                                <a data-toggle="collapse" data-parent="#all_bids" href="#{{$order->id}}">
                                      <h4 class="box-title">{{$order->order_no}}</h4>
                                      <span class="label label-success pull-right">${{$order->compensation}}</span>
                                  </a>  
                              </div>
                              <div id="{{$order->id}}" class="panel-collapse collapse">
                                @if(count($order->bids)>0)
                                <div class="box-body">
                                 <? $bids = $order->bids;?>
                                  @foreach($bids as $bid)
                                <? $user = User::find($bid->user_id); ?>
                                
                                    <div class="col-md-5 col-sm-6"><strong>Name</strong></div>
                                      <div class="col-md-7 col-sm-6">{{$user->first_name}} {{$user->last_name}}</div>
                                      <div class="col-md-5 col-sm-6"><strong>Total placed:</strong></div>
                                      <div class="col-md-7 col-sm-6">${{$bid->bid_ammount}}</div>
                                      @if($bid->bid_msg)
                                      <div class="col-md-5 col-sm-6"><strong>Message:</strong></div>
                                      <div class="col-md-7 col-sm-6">{{$bid->bid_msg}}</div>
                                      @endif
                                      <div class="col-md-5 col-sm-6"><b>Date Placed</b></div>
                                      <div class="col-md-7 col-sm-6">{{$bid->created_at}}</div>
                                      <div class="box-footer clearfix">
                                      </div>
                                  
                                  @endforeach
                                      <div class="box-footer clearfix">
                                        <a class="btn btn-sm btn-success btn-flat pull-left" href="orders/{{$order->id}}" >Assign Order</a>
                                      </div>
                                 </div>
                            </div>    
                        </div>
                        @else
                          No bids have been made on this Order yet, Check back later :)
                        @endif
                        @endforeach
                        @else
                          No bids are currently available all Orders have been assigned !
                        @endif
            </div>
      </div>
    </section>

  <!-- /.content-wrapper -->
 @stop
