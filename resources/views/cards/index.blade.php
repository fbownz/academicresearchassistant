@extends('layout')

@section('body')

<div class='content'>
	<div class='row'>
		<div class='col-md-12'>
		<h2>Card details will always come here</h2>
		@foreach($cards as $card)

		<div class='col-md-3'>
			<a href='cards/{{$card->id}}'> {{$card->title}}</a>

		</div>

		@endforeach

	</div>
</div>

</div>

@stop