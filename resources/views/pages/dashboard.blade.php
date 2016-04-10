@extends ('layout')

@section('body')

    <!-- Main content -->
    <section class="content">

      <?php 
        use Carbon\Carbon; 
        $a_orders = $orders->where('status','Available')->all();
        $no_of_orders = count($a_orders);

      $ac_orders = DB::table('orders')->where([
                    ['status','Active'],
                    ['user_id',Auth::user()->id],
                    ])->paginate(5);
      $no_of_available = count($ac_orders);
      $approved_orders = DB::table('orders')->where([
                    ['approved','1'],
                    ['user_id',Auth::user()->id],
                    ]);
      
      $total_pending = Auth::user()->earnings->where('paid','0')->sum('total');
      $total_paid = Auth::user()->earnings->where('paid', '1')->sum('total');
      $unread_messages = Auth::user()->messages->where('unread' ,'1')->count();
      $total_messages = Auth::user()->messages->count();
      ?>

      <div class="row 
      @if (strpos($_SERVER['REQUEST_URI'], "q") !== false) 
            hide
            @endif 
            ">
        <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="info-box bg-blue">
            <span class="info-box-icon"><a href={{url('orders')}}><i class="fa fa-list"></i></a></span>

            <div class="info-box-content">
              <span class="info-box-text">Orders</a></span>
              <span class="info-box-number">{{$no_of_orders}} Available</span>
              <div class="progress">
                <div class="progress-bar" style="width: 40%"></div>
              </div>
                <span class="progress-description">
                  <b>{{$no_of_available}}</b> Active
                </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="info-box bg-green">
            <span class="info-box-icon"><a href={{url('earnings')}}><i class="fa fa-money"></i></a></span>

            <div class="info-box-content">
              <span class="info-box-text">Pending Payout</span>
              <span class="info-box-number">${{$total_pending}}</span>
              <div class="progress">
                <div class="progress-bar" style="width: 60%"></div>
              </div>
                <span class="progress-description">
                  <b>${{$total_paid}}</b> Total Earned
                </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="info-box bg-red">
            <span class="info-box-icon"><a href={{url('inbox')}}><i class="fa fa-envelope-o"></i></a></span>

            <div class="info-box-content">
              <span class="info-box-text">Inbox</span>
              <span class="info-box-number">{{$unread_messages}} Unread </span>
              <div class="progress">
                <div class="progress-bar" style="width: 40%"></div>
              </div>
                <span class="progress-description">
                  <b>{{$total_messages}}</b> Messages
                </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        
      </div>

      <div class="row">
        <div class="col-md-12">
          <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title">Orders</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="col-md-7 col-xs-12 col-sd-12 col-lg-7">
                  <div class="box-group" id="all_orders">
                  <h4 class="box-title">Available Orders</h4>
                      <!-- Start iterating through the order data from the orderscontroller -->
                  @if(count($a_orders)!=0)
                  @foreach($a_orders as $order)
                  <?php   
                      $deadline = Carbon::parse("$order->deadline");
                      //$time_now =Carbon::now();
                      $timer= $deadline->diffForHumans()
                   ?>
                  <div class="panel box box-primary">
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
                                        <a class="btn btn-sm btn-success btn-flat pull-right" href="orders/{{$order->id}}" >Place Bid</a>
                                      </div>
                                 </div>
                            </div>    
                        </div>
                        @endforeach


                  <div class="box-footer clearfix">
                      <a class="btn btn-sm btn-default btn-flat pull-left" href="/orders" >View All Available Orders
                      </a>
                   </div>
                   @else
                        No Data to display, No orders available Check back later
                        @endif
                    </div>
               </div>
                <!-- /.col -->
                <div class="col-md-5 col-xs-12 col-sd-12 col-lg-5">
                  <div class="box-group" id="all_orders">
                    <h4 class="box-title">All your Active Orders</h4>
                <!-- Start iterating through the order data from the orderscontroller -->
                  @if(count($ac_orders) != 0)
                  @foreach($ac_orders as $order)
                  <?php   
                      $deadline = Carbon::parse("$order->deadline");
                      //$time_now =Carbon::now();
                      $timer= $deadline->diffForHumans()
                   ?>
                <div class="panel box box-primary">
                              <div class="box-header with-border">
                                <a data-toggle="collapse" data-parent="#all_orders" href="#{{$order->id}}">
                                      <h4 class="box-title">{{$order->order_no}}</h4>
                                      <span class="label label-success pull-right">${{$order->compensation}}</span>
                                  </a>  
                              </div>
                              <div id="{{$order->id}}" class="panel-collapse collapse">
                                <div class="box-body">
                                  
                                    <div class="col-sm-6"><strong>Order Type:</strong></div>
                                      <div class="col-sm-6">{{$order->type_of_product}}</div>
                                      <div class="col-sm-6"><strong>Topic:</strong></div>
                                      <div class="col-sm-6">{{$order->subject}}</div>
                                      <div class="col-sm-6"><strong>Pages:</strong><small>(275 Words/Page)</small></div>
                                      <div class="col-sm-6">{{$order->word_length}}, <strong>{{$order->spacing}} Spaced</strong></div>
                                      <div class="col-sm-6"><strong>Academic Level:</strong></div>
                                      <div class="col-sm-6">{{$order->academic_level}}</div>
                                      <div class="col-sm-6"><strong>Deadline:</strong></div>
                                      <div class="col-sm-6">{{$timer}}</div>
                                      <div class="col-sm-6"><strong>Reference Style:</strong></div>
                                      <div class="col-sm-6">{{$order->style}}</div>
                                      <div class="col-sm-6"><strong>Number of Sources:</strong></div>
                                      <div class="col-sm-6">{{$order->no_of_sources}}</div>
                                      <div class="col-sm-6"><strong>Order Total:</strong></div>
                                      <div class="col-sm-6 text-green">${{$order->compensation}}</div>
                                      <div class="box-footer clearfix">
                                        <a class="btn btn-sm btn-success btn-flat pull-left" href="orders/{{$order->id}}" >View Order</a>
                                      </div>
                                 </div>
                            </div>    
                        </div>
                        @endforeach
                        

                  
                  <div class="box-footer clearfix">
                      <a class="btn btn-sm btn-default btn-flat pull-left" href="#" >View All Active Orders
                      </a>
                   </div>
                   @else
                        No Data to display, no orders active, bid on an order to be assigned one.
                        @endif
                          </div>
                 </div>

                <!-- </div> -->
                <!-- /.col -->
              </div>
              <!-- /.row -->
            </div>
            <!-- ./box-body -->
            </div>
          </div>
          <!-- /.box -->
        <!-- /.col -->
      </div>
    </section>
  <!-- /.content-wrapper -->

@stop