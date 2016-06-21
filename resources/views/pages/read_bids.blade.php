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
          <div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">All Orders with Bids</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              @if(count($order_bids)>0)
              <div class="box-group" id="all_bids">
              @foreach($order_bids as $order_bid)
              @if(count($order_bid->bids)>0)
                <div class="panel box box-success">
                  <div class="box-header with-border">
                    <h4 class="box-title">
                      <a data-toggle="collapse" data-parent="#all_bids" href="#{{$order_bid->id}}">
                        {{$order_bid->order_no}}
                      </a>
                    </h4>
                    <span class="label label-success pull-right">${{$order_bid->compensation}}</span>
                  </div>
                  <div id="{{$order_bid->id}}" class="panel-collapse collapse">
                    <div class="box-body">
                      @foreach($order_bid->bids as $bid)
                       <? $user = User::findorfail($bid->user_id); ?>
                                   <div class="user-panel">
                                    <div class="pull-left image">
                                      <img src="{{$user->prof_pic_url}}" class="img-circle" alt="{{$user->first_name}} Avi">
                                    </div>   
                                  </div>                       
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
                                      <div class="col-xs-12">
                                      <a class="btn btn-sm btn-success btn-flat pull-right" href="/orders/{{$order_bid->id}}">Assign</a>
                                    </div>
                                      <br/>
                                      <br/>

                                      
                      @endforeach
                    </div>
                  </div>
                </div>
                @endif
                @endforeach
              </div>
              @else
              <p>All Orders have been assigned; Check Back Later<p>
              @endif
            </div>
            <!-- /.box-body -->
          </div>
      </div>
    </div>
    </section>

  <!-- /.content-wrapper -->
 @stop
