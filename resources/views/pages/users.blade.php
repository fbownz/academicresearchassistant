@extends ('layout')

@section('body')
    <!-- Main content -->
    <section class="content">
	<div class="row">
        <div class="col-md-12">
          <div class="box box-success">
	            <div class="box-header with-border">
	              <h3 class="box-title">Users</h3>

	              <div class="box-tools pull-right">
	                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
	                </button>
	                
	              </div>
	            </div>
            	<div class="box-body">
            		<table class="table table-bordered table-striped">
            			<thead>
            				<tr>
            					<th>Writer</th>
            					<th>Processing</th>
            					<th>Revision</th>
            					<th>Delivered</th>
            					<th>Approved</th>
            					<th>Late</th>
            				</tr>
            			</thead>
            			<tbody>
            				@foreach($users as $user)
            				<tr>
            					<td>{{$user->first_name}}</td>
            					<td>{{$user->orders->where('status','Active')->count()}}</td>
            					<td>{{$user->orders->where('status','Active-Revision')->count()}}</td>
            					<td>{{$user->orders->where('is_complete',1)->count()}}</td>
            					<td>{{$user->orders->where('approved', 1)->count()}}</td>
            					<td>{{$user->orders->where('is_late',1)->count()}}</td>
            				</tr>
            				@endforeach
            			</tbody>
            		</table>
               </div>
      	  </div>
        </div>
    </div>
          
    </section>
  <!-- /.content-wrapper -->

@stop