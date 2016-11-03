@extends ('layout')

@section('body')

<?php 
  use Carbon\Carbon; 
  use App\Order;
  use App\Earning; 
  use App\User;
  use App\Fine;
   if (!Auth::user()->ni_admin) {
    // Work in progress earnings
    $wp_earnings = Auth::user()->orders()->where('is_complete', null)->sum('compensation');

    $pe_earnings = Auth::user()->earnings()->where('paid', '0')->orderBy('created_at', 'desc')->get();
    $pa_earnings = Auth::user()->earnings()->where('paid', '1')->orderBy('created_at', 'desc')->get(); 

    

    //fines 
    $pe_fines = Auth::user()->fines->where('status', 1);
    //Paid fines
    $pa_fines = Auth::user()->fines->where('status', 0);

    // Total payments
    $total_paid = Auth::user()->earnings()->where('paid', '1')->sum('total') - Auth::user()->fines()->where('status', 0)->sum('total_fine');
    $total_pending = Auth::user()->earnings()->where('paid', '0')->sum('total') - Auth::user()->fines()->where('status', 1)->sum('total_fine');
  }
  else{
    // Work in Progress Earnings
    $wp_earnings = Order::where('user_id' , '>',1)->where('is_complete',null)->sum('compensation');

    $pe_earnings = Earning::where('paid', '0')->orderBy('created_at', 'desc')->get();
    $pa_earnings = Earning::where('paid', '1')->orderBy('created_at','desc')->get();
    

    $total_paid = Earning::where('paid','1')->sum('total') - Fine::where('status', 0)->sum('total_fine');
    $total_pending = Earning::where('paid', '0')->sum('total') - Fine::where('status', 1)->sum('total_fine');

    //active_Writers
    $active_writers = User::has('orders')->get();

  }
?>


    <!-- Main content -->
    <section class="content">
      
      <div class="row">
        <div class="col-md-12">
          @if(count(Session('approved_order')))
        
              <div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                 <? $approved_order = Session('approved_order'); ?>
                {{$approved_order}}
              </div>
          @elseif(count(Session('error')))
        
              <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                <h4><i class="icon fa fa-ban"></i> Error!</h4>
                 <? $error = Session('error'); ?>
                {{$error}}
              </div>
              @endif

          <div class="box box-success">
           <div class="col-md-4 border-right" style="border-color:green">
              <div class="box-header with-border">
                <h3 class="box-title">In Progress <br/>
                  <small>Work in Progress</small></h3>
              </div>
                <div class="box-body">
                  
                    <small>$</small>{{$wp_earnings}}
                </div>
            </div>
           <div class="col-md-4 border-right" style="border-color:green"> 
              <div class="box-header with-border">
                <h3 class="box-title">Pending <br>
                  <small>Available to Withdraw</small></h3>
              </div>
                <div class="box-body">
                  
                    <small>$</small>{{$total_pending}}
                </div>
            </div>
           <div class="col-md-4 border-right-left" style"border-color:green">
              <div class="box-header with-border">
                <h3 class="box-title">Total Paid</h3>
              </div>
                <div class="box-body">
                  
                    <small>$</small>{{$total_paid}} <br />
                    Recent payment $ 0.00
                </div>
            </div>
          </div>
        </div>
        
        @if(Auth::user()->ni_admin)
        <!-- Admin content starts here.  -->
        <div class="col-md-12">
          <div class="box box-success">
            <div class="box-header with-border">
              <div class="box-title">
                <h3>Writers Earning Summary</h3>
              </div>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                
              </div>
            </div>
            <div class="box-body">
              
              <!-- start of active writers datatable -->
              <table class="table" id="active_writers_table" cellspacing="0" width="100%" style="width:100%">
                <thead>
                  <tr>
                    <th>Name</th>
                    <th>Pending payment</th>
                  </tr>
                </thead> 
                <tfoot>
                  <tr>
                    <th>Name</th>
                    <th>Pending payment</th>
                  </tr>
                </tfoot>
                <tbody>
                  @foreach($active_writers as $active_writer)
                    <?
                      $ttl_pending = $active_writer->earnings()->where('paid','0')->sum('total') - $active_writer->fines()->where('status',1)->sum('total_fine'); 
                    ?>
                    <tr>
                    <td class="col-md-8">
                      <div class="box box-success box-solid collapsed-box">
                            <div class="box-header with-border">
                              {{$active_writer->first_name}} {{$active_writer->last_name}}

                              <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                </button>
                              </div>
                              <!-- /.box-tools -->
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body" style="display: none;">
                                <div class="box-body">
                                      
                                      <div class="col-md-12">
                                      <div class="col-md-3 col-sm-12 title"><strong>Pending Orders:</strong> <span class="text-green">${{number_format($active_writer->earnings()->where('paid', '0')->sum('total'),2)}}</span></div>
                                      <div class="col-md-3 col-sm-12 title"><strong>Pending Fines:</strong> <span class="text-red">-${{number_format($active_writer->fines()->where('status', '1')->sum('total_fine'), 2)}}</span></div>
                                      <div class="col-md-3 col-sm-12 title"><span class="label label-success"><strong>Pending Total:</strong>${{number_format($ttl_pending, 2)}}</span></div>
                                      <div class="col-md-3 col-sm-12 title"><strong>*In Progress Payments:</strong> <span class="text-green">${{number_format($active_writer->orders()->where('status', 'Delivered')->where('approved', null)->sum('compensation'),2)}}<span></div>
                                      <div class="col-md-3 col-sm-12 title"><strong>Total Paid:</strong> <span class="text-green">${{number_format($active_writer->earnings()->where('paid', '1')->sum('total'),2)}}<span></div>
                                      </div>
                                      <div class="box-footer ">
                                        @if($ttl_pending > 0)
                                        <a class="btn btn-sm btn-success btn-flat pull-right" href="/earnings/approve/{{$active_writer->id}}" >Approve payment</a>
                                        @endif
                                      </div>
                                      <div class="col-md-12 title text-red">
                                        <strong>Fines</strong>
                                        @if($active_writer->fines()->where('status','1')->count() <= 0)
                                          <small><br>No fines recorded yet</small>
                                        @endif
                                      </div>
                                      @foreach($active_writer->fines()->where('status','1')->orderBy('created_at', 'desc')->get() as $fine)
                                      @if ($fine->order()->count() > 0)
                                      <div class="col-md-5 col-sm-6 text-light-blue"><strong>Order No</strong></div>
                                      <div class="col-md-7 col-sm-6"><a href="/orders/{{$fine->order->id}}#fines">{{$fine->order->order_no}}</a></div>
                                      <div class="col-md-5 col-sm-6 text-light-blue"><strong>Reason</strong></div>
                                      <div class="col-md-7 col-sm-6 text-yellow">{{$fine->reason}}</div>
                                      <div class="col-md-5 col-sm-6 text-light-blue"><strong>Total Fine</strong></div>
                                      <div class="col-md-7 col-sm-6 text-yellow">-${{$fine->total_fine}}</div>
                                      <div class="box-footer clearfix"> 
                                      </div>
                                      @else
                                      No Order found. <small>Was it deleted?</small>
                                      Order ID = {{$earning->order_id}}
                                      @endif
                                      @endforeach
                                      <div class="col-md-12 title">
                                        <strong>Order payments</strong>
                                      </div>
                                      @foreach($active_writer->earnings()->where('paid','0')->orderBy('created_at', 'desc')->get() as $earning)
                                      @if ($earning->order()->count() > 0)
                                      <div class="col-md-5 col-sm-6"><strong>Order No</strong></div>
                                      <div class="col-md-7 col-sm-6"><a href="/orders/{{$earning->order->id}}">{{$earning->order->order_no}}</a></div>
                                      <div class="col-md-5 col-sm-6"><strong>Fine</strong></div>
                                      <div class="col-md-7 col-sm-6">${{$earning->late_fee}}</div>
                                      <div class="col-md-5 col-sm-6"><strong>Bonus</strong></div>
                                      <div class="col-md-7 col-sm-6">${{$earning->bonus}}</div>
                                      <div class="col-md-5 col-sm-6"><strong>Total</strong></div>
                                      <div class="col-md-7 col-sm-6 text-green">${{$earning->total}}</div>
                                      <div class="box-footer clearfix"> 
                                      </div>
                                      @else
                                      No Order found. <small>Was it deleted?</small>
                                      Order ID = {{$earning->order_id}}
                                      @endif
                                      @endforeach
                                </div>
                            </div>
                            <!-- /.box-body -->
                          </div>


                    </td>
                    <td style=" width: 30%;">${{number_format($ttl_pending, 2)}}</td>
                    </tr>
                  @endforeach
                </tbody> 
              </table> 
              
            </div>
          </div>
        </div>
        <!-- End of Admin Content -->
        @endif
        <div class="col-md-12">
            <div class="box box-success">
              <div class="box-header with-border">
                <div class="box-title">
                  <h3 class="box-title">Pending Earnings</h3>
                </div>
                <div class="box-tools pull-right">
                  <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                  </button>
                  
                </div>
              </div>
              <div class="box-body table-responsive">
                <table class="table table-bordered table-striped" id="pe_earnings_table">
                  <thead>
                    <tr>
                      <th>Earning ID</th>
                      @if(Auth::user()->ni_admin)
                      <th>Writer</th>
                      @endif
                      <th>Order No</th>
                      <th>Date Approved</th>
                      <th>Payment Status</th>
                      <th>Bonus</th>
                      <th>Total Fine</th>
                      <th>Amount</th>
                      <th>Total</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>Earning ID</th>
                      @if(Auth::user()->ni_admin)
                      <th>Writer</th>
                      @endif
                      <th>Order No</th>
                      <th>Date Approved</th>
                      <th>Payment Status</th>
                      <th>Bonus</th>
                      <th>Total Fine</th>
                      <th>Amount</th>
                      <th>Total</th>
                    </tr>
                  </tfoot>
                  <tbody>
                    @if($pe_earnings->count())
                      @foreach($pe_earnings as $earning)
                      <tr>
                        <td>{{$earning->id}}</td>
                        @if(Auth::user()->ni_admin)
                        <td>{{$earning->user->first_name}}</td>
                        @endif
                        @if(count($earning->order) > 0)
                        <td><a href="/orders/{{$earning->order_id}}">{{$earning->order->order_no}}</a></td>
                        @else
                        <td>No Order found. <small>Was it deleted?</small></td>
                        @endif
                        <td>{{$earning->created_at}}</td>
                        <td><? 
                        if($earning->paid !== 1){
                          echo"Pending Payment";
                        }else{
                          echo"Paid";
                        }?>
                        </td>
                        <td>${{$earning->bonus}}</td>
                        <? $total_fine = $earning->order->fines()->where('status', 1)->sum('total_fine') ?>
                        <td>-${{$total_fine}}</td>
                        <td>${{$earning->earning}}</td>
                        <td>${{$earning->total-$total_fine}}</td>
                      </tr>
                      @endforeach
                    @else
                     No Data to display. You haven't had any orders approved yet
                    @endif
                  </tbody>
                </table>
              </div>
           </div>
        </div>
        <div class="col-md-12">
          <div class="box box-success">
            <div class="box-header with-border">
              <div class="box-title">
                <h3>Paid Earnings</h3>
              </div>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                
              </div>
            </div>
            <div class="box-body table-responsive">
              <table class="table table-bordered table-striped" id="pa_earnings_table">
                <thead>
                  <tr>
                    <th>Earning ID</th>
                    @if(Auth::user()->ni_admin == 1)
                    <th>Writer</th>
                    @endif
                    <th>Order No</th>
                    <th>Date Paid</th>
                    <th>Date Approved</th>
                    <th>Payment Status</th>
                    <th>Bonus</th>
                    <th>Total Fines</th>
                    <th>Amount</th>
                    <th>Total</th>
                  </tr>
                </thead>
                <tfoot>
                  <tr>
                    <th>Earning ID</th>
                    @if(Auth::user()->ni_admin == 1)
                    <th>Writer</th>
                    @endif
                    <th>Order No</th>
                    <th>Date Paid</th>
                    <th>Date Approved</th>
                    <th>Payment Status</th>
                    <th>Bonus</th>
                    <th>Total Fines</th>
                    <th>Amount</th>
                    <th>Total</th>
                  </tr>
                </tfoot>
                <tbody>
                  @if(count($pa_earnings) > 0)
                  @foreach($pa_earnings as $earning_p)
                    <tr>
                      <td>{{$earning_p->id}}</td>
                      @if(Auth::user()->ni_admin == 1)
                      <td>{{$earning_p->user->first_name}}</td>
                      @endif
                      @if(count($earning_p->order) > 0)
                      <? $total_pfine = $earning_p->order->fines()->where('status', 0)->sum('total_fine') ?>
                      <td><a href="/orders/{{$earning_p->order_id}}">{{$earning_p->order->order_no}}</a></td>
                      @else
                      <? $total_pfine = $earning_p->late_fee ?>
                      <td>No Order found. <small>Was it deleted?</small></td>
                      @endif
                      <td>{{$earning_p->updated_at}}</td>
                      <td>{{$earning_p->created_at}}</td>
                      <td>
                        <? 
                      if($earning_p->paid != 1){
                        echo"Pending Payment";
                      }else{
                        echo"Paid";
                      }?>
                      </td>
                      <td>${{$earning_p->bonus}}</td>
                      <td>-${{$total_pfine}}</td>
                      <td>${{$earning_p->earning}}</td>
                      <td>${{$earning_p->total - $total_pfine }}</td>
                    </tr>
                  @endforeach
                  @else
                  No Data to Display. You haven't had any payments yet
                  @endif
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>

  <!-- /.content-wrapper -->
 @stop
