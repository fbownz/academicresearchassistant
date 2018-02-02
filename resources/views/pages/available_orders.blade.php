@extends('layout')

@section('body')

<?php use Carbon\Carbon; 
?>
    <!-- Main content -->
    <section class="content">

      <div class="box box-default">
            <div class="box-header with-border">
              <h3 class="box-title">Available Orders</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                </button>
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table class="table table-striped dataTable" id="availabe_orders" cellspacing="0" width="100%" style="width:100%">
                <thead>
                  <tr>
                    <th>Order</th>
                  </tr>
                </thead> 
                <tfoot>
                  <tr>
                    <th>Order</th>
                  </tr>
                </tfoot>
                <tbody>
                @if(!$or ->count())
                  <tr>
                    <td>All orders have been assigned. Check back in a few or
                    Try Refreshing</td>
                  </tr>
                @else
                  @foreach($or as $order)
                  <?
                    $deadline =Carbon::parse("$order->deadline")->diffForHumans();
                  ?>
                  <tr>
                    <td>
                      <div class="panel box box-success">
                                  <div class="box-header with-border">
                                      <a data-toggle="collapse" data-parent="#all_orders" href="#{{$order->id}}">
                                            <span style="font-size:26px">{{$order->order_no}}</span>
                                            <!-- <span class="pull-right" style="font-size:18px">${{$order->compensation}}</span> -->
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
                                          <div class="col-md-7 col-sm-6"><b>{{$deadline}}</b> <br > {{$order->deadline}}</div>
                                          <div class="box-footer clearfix">
                                           <span class="pull-left text-green" style="font-size:16px">{{$order->bids()->count()}} Bids placed</span>
                                            <a class="btn btn-sm btn-success btn-flat pull-right" style="font-size:23px" href="orders/{{$order->id}}" >Place Bid</a>
                                          </div>
                                    </div>
                                  </div>    
                            </div>
                    </td>
                  </tr>
                  @endforeach
                @endif
                </tbody>
              </table>

            </div>
            <!-- /.box-body -->
          </div>

	</section>
    <!-- /.content -->

@stop