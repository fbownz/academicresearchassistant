<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="stylesheet" href="https://adminlte.io/themes/AdminLTE/bower_components/bootstrap/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://adminlte.io/themes/AdminLTE/bower_components/font-awesome/css/font-awesome.min.css ">
        <link rel="stylesheet" href="https://adminlte.io/themes/AdminLTE/dist/css/AdminLTE.min.css" >
        <link rel="stylesheet" href="https://adminlte.io/themes/AdminLTE/dist/css/skins/skin-blue.min.css">

    </head>
    <body class="skin-blue">
        <section class="content">
            <div class="box box-primary direct-chat direct-chat-primary" style="border:4px solid #3c8dbc;">
                <div style="font-size:20px; text-align:center; font-weight:300px">
                    <strong>{{$domain}}</strong>
                </div>
                    <div class="box-header" style="min-height:240px; vertical-align:middle; text-align:center; border-bottom:1px solid #3c8dbc; background: #3c8dbc url('http://wmse.s3-website.eu-central-1.amazonaws.com/assets/img/nbi.jpg') no-repeat center top/cover;padding-top: 30px;overflow: hidden;">
                        <p style="font-size: 30px; line-height: 40px; text-transform:UPPERCASE;color:#fff; ">Congratulations!.</p>
                       <span style="font-size: 18px; line-height: 40px; color:#333; border-bottom:#00ff04 solid">
                           Your order {{$order->title}} has been placed successfully
                       </span>
                    </div>
                    <div class="box-body"style="border-bottom:2px solid #3c8dbc;">
                        <p style="text-transform:UPPERCASE;Margin-top:20px;Margin-bottom:10px;color:#6a6a6a;font-family:Helvetica,Arial,sans-serif;font-size:30px;font-weight:700;line-height:1.3em;text-align:center">What to do next?</p>
                        <hr style="margin-bottom: 7px">
                        <ol>
                            <li>
                            <h3>Check your order</h3>
                        <p>Sign in to your account. Add instructions upload a file if needed.</p>
                            </li>
                            <li>
                            <h3>Assign a Writer</h3>
                        <p>Chat with writers interested in you project and hire the best one*</p>
                            </li>
                            <li>
                            <h3>Keep it active </h3>
                        <p>Follow up on writer’s progress.
Get to know where the writer has reached with the project. Request for completed sections.</p>
                            </li>
                            <li>
                            <h3>Collect your paper</h3>
                        <p>Download 100% completed paper and leave a feedback on writer’s work</p>
                            </li>
                        </ul>
                        <hr>
                        <p style="text-align: center;"><span><a href="http://app.{{$domain}}/dashboard/order/{{$order->id}}" style="Margin:0;border:0 solid #67c079;border-radius:5px;color:#fff;display:inline-block;font-family:Helvetica,Arial,sans-serif;font-size:24px;font-weight:400;line-height:1.3em;margin:0;padding:10px 17px 10px 17px;text-align:center;text-decoration:none;background:#3c8dbc;">My Order</a></span><br>
                            <span style="display:inline-block;font-weight:700;line-height:50px;vertical-align:middle;">Thank you! </span><br>for creating an account and choosing {{$domain}} as your Custom paper provider </p>
                    </div>
                    <div class="box-footer small">
                        &copy; <?php echo date("Y"); ?>, {{$domain}} <br>
                        This message was created automatically by our email system, please do not reply.<br>
                        You have received this e-mail because you are subscribed as a registered user of our service {{$domain}}. <a href="https://{{$domain}}/unsubscribe">Unsubscribe from emails</a>, if you don't want to receive messages from our mailing system anymore.<br>
                    </div>
            </div>
        </section>

    </body>
</html>