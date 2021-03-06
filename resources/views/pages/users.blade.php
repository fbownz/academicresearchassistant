@extends ('layout')

@section('body')
    <!-- Main content -->
    <section class="content">
	<div class="row">
        <div class="col-md-12">
             @if(Session::has('message'))
          <div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                <h4><i class="icon fa fa-info"></i>Success!</h4>
                 <? $message = Session('message'); ?>
                {{$message}}
              </div>
        @endif
          <div class="box box-success">
	            <div class="box-header with-border">
	              <h3 class="box-title">Users</h3>
                    
	              <div class="box-tools pull-right">
	                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
	                </button>
	                
	              </div>
	            </div>
            	<div class="box-body">
            		<table class="table table-bordered table-striped" id="users_table">
            			<thead>
            				<tr>
            					<th>Writer</th>
            					<th>Delivered</th>
            					<th>Approved</th>
                                <th>Processing</th>
                                <th>Revision</th>
            					<th>Registration IP</th>
                                <th>Status</th>
            				</tr>
            			</thead>
            			<tbody>
                            
            				@foreach($users as $user)
                            
            				<tr>
            					<td>
                                    <a href="/writer/{{$user->id}}">
                                    <span style="padding:3px;">
                                        <img src="{{$user->prof_pic_url}}" style="max-width:50px;">
                                    </span>
                                    {{$user->first_name}}
                                    </a>
                                </td>
                                <td>{{$user->orders->where('is_complete',1)->count()}}</td>
                                <td>{{$user->orders->where('approved', 1)->count()}}</td>
            					<td>{{$user->orders->where('status','Active')->count()}}</td>
            					<td>{{$user->orders->where('status','Active-Revision')->count()}}</td>
            					<td>{{$user->ip}}</td>
                                <td><a href="/writer/deactivate/{{$user->id}}">
                                      <button type="button" class="btn btn-block btn-danger">Deactivate User</button>
                                    </a>
                                </td>
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