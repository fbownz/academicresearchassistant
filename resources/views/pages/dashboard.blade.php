@extends ('layout')

@section('body')

    <!-- Main content -->
    <section class="content">
      <?php 
        use Carbon\Carbon;
        use App\Bid;
        use App\Order;
        use App\Earning;
        use App\Message;
        

            $a_orders = Order::where('status','Available')->orderBy('created_at', 'desc')->paginate(10); 
            $no_of_orders = Order::where('status','Available')->count();

        if (Auth::user()->ni_admin == 0) 
        {
            
            //Active Orders
            $ac_orders = Auth::user()->orders()->where('status','Active')->orderBy('created_at', 'desc')->paginate(5);
            $no_of_active = Auth::user()->orders()->where('status','Active')->count();

            // Delivered orders
            $comp_orders = Auth::user()->orders()->where('status','Delivered')->orderBy('created_at', 'desc')->get();
            $no_comp_orders = Auth::user()->orders()->where('status','Delivered')->count() ;

            // Approved orders (Orders that have been paid )
            $app_orders = Auth::user()->earnings()->orderBy('created_at', 'desc')->get();
            $no_App_orders = Auth::user()->earnings()->count();


          // We go through relationships with laravel Eloquent ORM 
          // We get pending orders
          $my_pending_bids= Bid::with('user','order');
          $no_my_active_bids=count($my_pending_bids->whereHas('user',function($q)
          {
            $q->where('id',Auth::user()->id);
          })
          ->whereHas('order',function($q)
          {
            $q->where('status','Available');
          })
          ->get());

          
          
          $total_pending_approved = Auth::user()->earnings->where('paid',0)->sum('total');
          $total_paid = Auth::user()->earnings->where('paid', 1)->sum('total');
          $unread_messages = Auth::user()->messages->where('unread' ,'1')->count();
          $total_messages = Auth::user()->messages->count();
        }
        else
        {
          //Active Orders
          $ac_orders =  Order::where('status','Active')->orderBy('created_at', 'desc')->get();
          $no_of_active = Order::where('status', 'Active')->count();

          //Delivered Orders
          $comp_orders = Order::where('status','Delivered')->orderBy('created_at', 'desc')->get();
          $no_comp_orders = Order::where('status','Delivered')->count();

          //Approved Orders
          $app_orders = Earning::all();
          $no_App_orders = Earning::count();

          // We go through relationships with laravel Eloquent ORM 
          // We get pending orders
          $my_pending_bids= Bid::with('order');
          $no_my_active_bids=count($my_pending_bids->whereHas('order',function($q)
          {
            $q->where('status','Available');
          })
          ->get());

          //Totals
          $total_pending_approved = Earning::where('paid',0)->sum('total');
          $total_paid = Earning::where('paid',1)->sum('total');
          $unread_messages = Message::where('unread','1')->count();
          $total_messages = Message::count();
        }
         
      ?>


      <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box bg-blue">
            <span class="info-box-icon"><a href={{url('/u_bids')}}><i class="fa fa-book"></i></a></span>

            <div class="info-box-content">
              <span class="info-box-text">@if(Auth::user()->ni_admin)
                Current Bids 
                @else 
                My Current bids 
                @endif
                </span>
              <span class="info-box-number">{{$no_my_active_bids}} Bids</span>
              <div class="progress">
                <div class="progress-bar" style="width: 40%"></div>
              </div>
                <span class="progress-description">
                  <b><a @if(Auth::user()->ni_admin)
                    href ="/order_bids"
                    @else
                    href="/u_bids"
                    @endif
                    >View all Bids</a></b>
                </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box bg-blue">
            <span class="info-box-icon"><i class="fa fa-list"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Orders in Progress</span>
              <span class="info-box-number">{{$no_of_active}}</span>
              <div class="progress">
                <div class="progress-bar" style="width: 60%"></div>
              </div>
                <span class="progress-description">
                  <b>{{$no_comp_orders}}</b> Completed
                </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box bg-blue">
            <span class="info-box-icon"><i class="fa fa-list"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Past Orders</span>
              <span class="info-box-number">{{$no_App_orders}}</span>
              <div class="progress">
                <div class="progress-bar" style="width: 40%"></div>
              </div>
                <span class="progress-description">
                  <b>Approved orders</b>
                </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box bg-green">
            <span class="info-box-icon"><a href="/earnings"><i class="fa fa-money"></i></a></span>

            <div class="info-box-content">
              <span class="info-box-text"><sup>*</sup>Earnings</span>
              <span class="info-box-number">${{$total_pending_approved}} Pending</span>
              <div class="progress">
                <div class="progress-bar" style="width: 40%"></div>
              </div>
                <span class="progress-description">
                  <b>${{$total_paid}} Earned</b>
                </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        
      </div>

      <div class="row">
        <div class="col-md-12">
        @if(Auth::user()->ni_admin)
          <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title">Transactions Summary</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="col-md-7 col-xs-12 col-sd-12 col-lg-7">
                  <div class="box-group" id="all_orders">
                      <h4 class="box-title"> {{$no_of_orders}} Orders Available to bid</h4>
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
                                      <!-- <span class="label label-success pull-right">${{$order->compensation}}</span> -->
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
                                      @if(Auth::user()->ni_admin)
                                        <div class="col-md-5 col-sm-6"><strong>Payment Status</strong></div>
                                             @if($order->client_ID && $order->transactions->count())
                                              @if($order->transactions->last()->responseCode == "Y")
                                                <div class="col-md-7 col-sm-6 text-green"><strong>Paid: ${{$order->transactions->last()->total}}</strong></div>
                                              @else
                                                <div class="col-md-7 col-sm-6 text-red"><strong>Payment UnSuccessful!</strong></div>
                                              @endif
                                             @elseif(!$order->client_ID)
                                              <div class="col-md-7 col-sm-6">Added manually!</div>
                                             @else
                                             <div class="col-md-7 col-sm-6 text-red"><strong>Unpaid!</strong></div>
                                             @endif

                                        @endif
                                      <!-- <div class="col-md-5 col-sm-6"><strong>Order Total:</strong></div>
                                      <div class="col-md-7 col-sm-6 text-green">${{$order->compensation}}</div> -->
                                  
                                  
                                      <div class="box-footer clearfix">
                                        <a class="btn btn-sm btn-success btn-flat pull-left" href="/orders/{{$order->id}}" >View Order</a>
                                        <a class="btn btn-sm btn-success btn-flat pull-right" href="/orders/{{$order->id}}#bidonthis" >Place Bid</a>
                                      </div>
                                 </div>
                            </div>    
                        </div>
                        @endforeach


                  <div class="box-footer clearfix">
                      <a class="btn btn-sm btn-default btn-flat pull-left" href="/find_work" >Available Orders/Find Work
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
                        <h4 class="box-title">{{$no_of_active}} Active Orders</h4>
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
                                      <!-- <span class="label label-success pull-right">${{$order->compensation}}</span> -->
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
                                      <!-- <div class="col-sm-6"><strong>Order Total:</strong></div>
                                      <div class="col-sm-6 text-green">${{$order->compensation}}</div> -->
                                      <div class="box-footer clearfix">
                                        <a class="btn btn-sm btn-success btn-flat pull-left" href="/orders/{{$order->id}}" >View Order</a>
                                      </div>
                                 </div>
                            </div>    
                        </div>
                        @endforeach
                        

                  
                  <div class="box-footer clearfix">
                      <a class="btn btn-sm btn-default btn-flat pull-left" href="/orders#pending" >View All Active Orders
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
        @endif
          <!-- /.box -->
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
                      <h4 class="box-title"> {{$no_of_orders}} Orders Available to bid</h4>
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
                                      <!-- <span class="label label-success pull-right">${{$order->compensation}}</span> -->
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
                                      @if(Auth::user()->ni_admin)
                                        <div class="col-md-5 col-sm-6"><strong>Payment Status</strong></div>
                                             @if($order->client_ID && $order->transactions->count())
                                              @if($order->transactions->last()->responseCode == "Y")
                                                <div class="col-md-7 col-sm-6 text-green"><strong>Paid: ${{$order->transactions->last()->total}}</strong></div>
                                              @else
                                                <div class="col-md-7 col-sm-6 text-red"><strong>Payment UnSuccessful!</strong></div>
                                              @endif
                                             @elseif(!$order->client_ID)
                                              <div class="col-md-7 col-sm-6">Added manually!</div>
                                             @else
                                             <div class="col-md-7 col-sm-6 text-red"><strong>Unpaid!</strong></div>
                                             @endif

                                        @endif
                                      <!-- <div class="col-md-5 col-sm-6"><strong>Order Total:</strong></div>
                                      <div class="col-md-7 col-sm-6 text-green">${{$order->compensation}}</div> -->
                                  
                                  
                                      <div class="box-footer clearfix">
                                        <a class="btn btn-sm btn-success btn-flat pull-left" href="/orders/{{$order->id}}" >View Order</a>
                                        <a class="btn btn-sm btn-success btn-flat pull-right" href="/orders/{{$order->id}}#bidonthis" >Place Bid</a>
                                      </div>
                                 </div>
                            </div>    
                        </div>
                        @endforeach


                  <div class="box-footer clearfix">
                      <a class="btn btn-sm btn-default btn-flat pull-left" href="/find_work" >Available Orders/Find Work
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
                        <h4 class="box-title">{{$no_of_active}} Active Orders</h4>
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
                                      <!-- <span class="label label-success pull-right">${{$order->compensation}}</span> -->
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
                                      <!-- <div class="col-sm-6"><strong>Order Total:</strong></div>
                                      <div class="col-sm-6 text-green">${{$order->compensation}}</div> -->
                                      <div class="box-footer clearfix">
                                        <a class="btn btn-sm btn-success btn-flat pull-left" href="/orders/{{$order->id}}" >View Order</a>
                                      </div>
                                 </div>
                            </div>    
                        </div>
                        @endforeach
                        

                  
                  <div class="box-footer clearfix">
                      <a class="btn btn-sm btn-default btn-flat pull-left" href="/orders#pending" >View All Active Orders
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
    </section>
  <!-- /.content-wrapper -->

@stop