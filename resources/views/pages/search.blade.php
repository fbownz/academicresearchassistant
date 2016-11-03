@extends ('layout')

@section('body')

    <!-- Main content -->
    <section class="content">

      <?php 
        use Carbon\Carbon; 
        $a_orders = $orders
      ?>

      <div class="row">
        <div class="col-md-12">
          <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title">Search Results</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="col-xs-12">
                  <div class="box-group" id="all_orders">
                  <h4 class="box-title">Orders found</h4>
                      <!-- Start iterating through the order data from the orderscontroller -->
                  @if(count($a_orders)!== 0)
                  @foreach($a_orders as $order)
                  <?php   
                      $deadline = Carbon::parse("$order->deadline");
                      //$time_now =Carbon::now();
                      $timer= $deadline->diffForHumans();

                      $client_deadline = Carbon::parse("$order->client_deadline");
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
                                      @if(Auth::user()->ni_admin)
                                      <div class="col-md-5 col-sm-6"><strong>Client's Deadline:</strong></div>
                                      <div class="col-md-7 col-sm-6 text-light-blue">{{$client_deadline->format('F j, Y H:i A')}}</div>
                                      @endif
                                      <div class="col-md-5 col-sm-6"><strong>Deadline:</strong></div>
                                      <div class="col-md-7 col-sm-6 text-red">{{$timer}}</div>
                                      <div class="col-md-5 col-sm-6"><strong>Reference Style:</strong></div>
                                      <div class="col-md-7 col-sm-6">{{$order->style}}</div>
                                      <div class="col-md-5 col-sm-6"><strong>Number of Sources:</strong></div>
                                      <div class="col-md-7 col-sm-6">{{$order->no_of_sources}}</div>
                                      <div class="col-md-5 col-sm-6"><strong>Order Total:</strong></div>
                                      <div class="col-md-7 col-sm-6 text-green">${{$order->compensation}}</div>
                                      
                                  
                                  
                                      <div class="box-footer clearfix">
                                        <a class="btn btn-sm btn-success btn-flat pull-left" href="/orders/{{$order->id}}" >View Order</a>
                                        @if($order->status == 'Available')
                                        <a class="btn btn-sm btn-success btn-flat pull-right" href="/orders/{{$order->id}}" >Place Bid</a>
                                        @endif
                                      </div>
                                 </div>
                            </div>    
                        </div>
                        @endforeach
                   @else
                        No Data to display, No orders available Check back later
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