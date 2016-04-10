@extends ('layout')

@section('body')

<?php 
  use Carbon\Carbon; 
  use App\Order;
  use App\Earning; 
  if (Auth::user()->ni_admin != 1) {
    $earnings = Auth::user()->earnings()->orderBy('created_at', 'desc')->get();
  }
  else{
    $earnings = Earning::orderBy('created_at', 'desc')->get();
  }
  $total_paid = Auth::user()->earnings->where('paid', '1')->sum('total');
  $total_pending = Auth::user()->earnings->where('paid', '0')->sum('total');
?>

<? 
  // echo "Earnings ziko ". count($earnings);
?>
    <!-- Main content -->
    <section class="content">
      @if(Auth::user()->ni_admin !=1 )
      <div class="row">
        <div class="col-md-12">
          <div class="box box-success">
           <div class="col-md-4 border-right" style="border-right-color:green">
              <div class="box-header with-border">
                <h3 class="box-title">Earned</h3>
                <div class="box-body">
                  
                    <small>$</small>{{$total_paid}}
                </div>
              </div>
            </div>
           <div class="col-md-4 border-right" style="border-right-color:green"> 
              <div class="box-header with-border">
                <h3 class="box-title">Withdrawals</h3>
                <div class="box-body">
                  
                    <small>$</small>{{$total_paid}}
                </div>
              </div>
            </div>
           <div class="col-md-4 ">
              <div class="box-header with-border">
                <h3 class="box-title">Pending payout</h3>
                <div class="box-body">
                  
                    <small>$</small>{{$total_pending}}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      @endif
      <div class="row">
        <div class="col-md-12">
          <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title">Recent Earnings</h3>
            </div>
             <div class="box-body">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Earning ID</th>
                    <th>Order No</th>
                    <th>Date Approved</th>
                    <th>Payment Status</th>
                    <th>Late Fee</th>
                    <th>Amount</th>
                    <th>Total</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($earnings as $earning)
                 <?php
                  
                  $order = Order::find($earning->order_id); 
                  
                  ?>
                  <tr>
                    <td>{{$earning->id}}</td>
                    <td><a href="/orders/{{$earning->order_id}}">{{$order->order_no}}</a></td>
                    <td>{{$earning->created_at}}</td>
                    <td><? 
                    if($earning->paid != 1){
                      echo"Pending Payment";
                    }else{
                      echo"Paid";
                    }?>
                  </td>
                    <td>${{$earning->late_fee}}</td>
                    <td>${{$earning->earning}}</td>
                    <? $total = $earning->earning - $earning->late_fee ?>
                    <td>${{$total}}</td>
                  </tr>
                  @endforeach
                </tbody>
                <tfoot>
                </tfoot>
              </table>
             </div>
          </div>
        </div>
      </div>
    </section>

  <!-- /.content-wrapper -->
 @stop
