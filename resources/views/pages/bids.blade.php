@extends ('layout')

@section('body')

<?php 
  use Carbon\Carbon; 
  use App\Order;
  use App\Bid;

  // $my_bids =App\Bid::whereHas('user', function ($q) 
  //       {
  //         $q->where('id', Auth::user()->id);
  //       } )->get(); 
  $my_bids = Auth::user()->bids()->orderBy('created_at','desc')->paginate(50);

?>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12 col-sd-12 ">
          <div class="box box-solid">
            <div class="box-body">
              @if(count($my_bids)>0)
            <table class="table responsive" id="u_bids">
              <thead>
                <tr>
                <th>Order ID</th>
                <th>Subject</th>
                <th>Status</th>
                <th>Amount</th>
                <th>Date Placed</th>
                </tr>
              </thead>
              <tbody> 
                @foreach($my_bids as $bid)                
                <?
                $order_bid =$bid->order()->paginate(5);
                ?>
                
                <tr>
                    @if(count($order_bid)>0)
                    @foreach($order_bid as $order_detail)
                    <td><a href="/orders/{{$order_detail->id}}">{{$order_detail->order_no}}</a></td>
                    <td>{{$order_detail->subject}}</td>
                    @if($order_detail->status =="Available")
                    <td>No Writer Selected yet</td>
                    @elseif($order_detail->user_id == Auth::User()->id)
                    <td>Your Bid was selected</td>
                    @else
                    <td>Your Bid was not selected</td>
                    @endif
                    <td>${{$bid->bid_ammount}}</td>
                    <td>{{ Carbon::parse($bid->created_at)->diffForHumans()}}</td>
                    @endforeach
                    @endif
                </tr>
                @endforeach

              </tbody>
            </table>
            </div>
            <div class="box-footer">
              {!!$my_bids->render()!!}
              @else
                <p>You haven't made any bids yet <a href="/orders"> Place your first bid</a></p>
                @endif
            </div>
            <!-- /.box-body -->
          </div>
      </div>
    </div>
    </section>

  <!-- /.content-wrapper -->
 @stop
