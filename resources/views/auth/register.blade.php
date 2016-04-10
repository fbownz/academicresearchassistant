@extends('layouts.app')

@section('content')
<form role="form" method="POST" action="{{ url('/register') }}">
    {!! csrf_field() !!}
      <div class="form-group {{ $errors->has('first_name') ? ' has-error' : '' }} has-feedback">
        <input type="text" class="form-control" name="first_name" placeholder="First name" value="{{ old('first_name') }}">
        <span class="glyphicon glyphicon-user form-control-feedback text-green"></span>
        @if ($errors->has('first_name'))
            <span class="help-block">
                <strong>{{ $errors->first('first_name') }}</strong>
            </span>
        @endif
      </div>
      <div class="form-group {{ $errors->has('last_name') ? ' has-error' : '' }} has-feedback">
        <input type="text" class="form-control" name="last_name" placeholder="last name" value="{{ old('last_name') }}">
        <span class="glyphicon glyphicon-user form-control-feedback text-green"></span>
        @if ($errors->has('last_name'))
            <span class="help-block">
                <strong>{{ $errors->first('last_name') }}</strong>
            </span>
        @endif
      </div>
      <div class="form-group has-feedback {{ $errors->has('email') ? ' has-error' : '' }}">
        <input type="email" class="form-control" placeholder="Email Address" name="email" value="{{old('email')}}">
        <span class="glyphicon glyphicon-envelope form-control-feedback text-green"></span>
        @if ($errors->has('email'))
        <span class="help-block">
            <strong>{{ $errors->first('email') }}</strong>
        </span>
        @endif
      </div>
      <div class="form-group has-feedback {{ $errors->has('password') ? ' has-error' : '' }}">
        <input type="password" class="form-control" name="password" placeholder="Password">
        <span class="glyphicon glyphicon-lock form-control-feedback text-green"></span>
         @if ($errors->has('password'))
            <span class="help-block">
                <strong>{{ $errors->first('password') }}</strong>
            </span>
        @endif
      </div>
      <div class="form-group has-feedback {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
        <input type="password" class="form-control" placeholder="Retype password" name="password_confirmation">
        <span class="glyphicon glyphicon-log-in form-control-feedback text-green"></span>
         @if ($errors->has('password_confirmation'))
        <span class="help-block">
            <strong>{{ $errors->first('password_confirmation') }}</strong>
        </span>
        @endif
      </div>
      <div class="row">
        <div class="col-xs-8">
          <div class="checkbox icheck {{ $errors->has('checkbox') ? ' has-error' : '' }}">
            <label>
              <input type="checkbox" name="checkbox"> I agree to the <a href="#">terms</a>
              @if ($errors->has('password_confirmation'))
            <span class="help-block">
                <strong>{{ $errors->first('checkbox') }}</strong>
            </span>
        @endif
            </label>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-success btn-block btn-flat">Register</button>
        </div>
        <!-- /.col -->
        <span class="col-xs-12">
            <a href="/login" class="text-center">I already have an Account</a>
        </span>
        
      </div>
    </form>
@endsection
