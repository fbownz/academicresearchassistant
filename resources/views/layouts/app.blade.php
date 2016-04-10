<!DOCTYPE html>
<? use App\Http\Controllers\PagesController;
?>
<html lang="en">
<head>
   <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{PagesController::$site_title}} | {{PagesController::$page_title}} </title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.5 -->
  <link rel="stylesheet" href="/css/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="/css/AdminLTE.min.css">
  <link rel="stylesheet" href="/css/plugins/iCheck/square/green.css">
  <!-- <link rel="stylesheet" href="/css/skins/skin-green.min.css"> -->

</head>
<body class="hold-transition @if(PagesController::$page_title == 'Login Page') login-page
@elseif(PagesController::$page_title == 'Register A new Writers Account') register-page
@else() login-page
@endif">
<div class=" @if(PagesController::$page_title == 'Login Page') 
  login-box
@elseif(PagesController::$page_title == 'Register A new Writers Account')
  register-box
  @else() login-box
@endif
">
  <div class="
@if(PagesController::$page_title == 'Login Page') 
  login-logo
@elseif(PagesController::$page_title == 'Register A new Writers Account')
  register-logo
  @else() login-logo
@endif
">
    <a href="/"><b>Academic</b>ResearchAssistants</a>
  </div>
  <!-- logo -->
  <div class="
@if(PagesController::$page_title == 'Login Page') 
  login-box-body
@elseif(PagesController::$page_title == 'Register A new Writers Account')
  register-box-body
  @else() login-box-body
@endif
">
    <p class="login-box-msg">{{PagesController::$page_title}}</p>

    
    @yield('content')
  </div>
    </div>
<!-- Login|Register|Reset box end -->
  <div class="lockscreen-footer text-center">
    Copyright &copy; <?php echo date("Y"); ?> <b><a href="/" class="text-black">{{PagesController::$site_title}}</a>.</b><br> All rights reserved
  </div>

    <!-- JavaScripts -->
    <script src="/css/plugins/jQuery/jQuery-2.1.4.min.js"></script>
<!-- Bootstrap 3.3.5 -->
<script src="/css/bootstrap/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="/css/plugins/iCheck/icheck.min.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-green',
      radioClass: 'iradio_square-green',
      increaseArea: '20%' // optional
    });
  });
</script>
</body>
</html>
