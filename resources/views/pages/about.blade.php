@extends ('layout')


@section('content')

 <ol>
               @foreach ($maguyz as $mguy) 
               
               <li>{{$mguy}}</li>

               @endforeach
               </ol>
@stop