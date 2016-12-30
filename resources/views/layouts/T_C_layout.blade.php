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
  <!-- <link rel="stylesheet" href="/css/skins/skin-green.min.css"> -->

</head>
<body class="hold-transition login-page">
<div class="tc-box">
  <div class="login-logo">
    <a href="/"><b>Academic</b>ResearchAssistants</a>
  </div>
  <!-- logo -->
  <div class="login-box-body">
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
  <!-- GA tracking script -->
  <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-89615848-1', 'auto');
  ga('send', 'pageview');

</script>
</body>
</html>
