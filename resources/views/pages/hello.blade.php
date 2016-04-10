@extends('layout')

@section('content')

<!-- I have used the yeild method for Laravel -->
<h2>Welcome to ur first Framework</h2>
                <p>Hi {{$name}} We understand that is is your very first framework and we are very excited<br/>

                    I hope you are as excited </p>

                    <p>That being said, You have lots of work to do since this is something very very new to all of us.<br>
                        But we will manage. We will Effin Succeed <br>
                    Best of luck bro</p>

                    <h4>Another thing</h4>
                    One of your Cousins is getting married to the other.<br> Who are they?
                    <ol>
                        @foreach ($maguyz as $mguy)
                        <li>{{$mguy}}</li>
                        @endforeach
                    </ol>

@stop
