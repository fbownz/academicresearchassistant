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
        <link rel="stylesheet" href="http://academicresearchassistants.com/skin-blue.min.css">

    </head>
    <body class="skin-blue">
        <div class="row">
            <div class="col-md-12">
                <section class="content" style="margin-left:10%;margin-right: 10%">
            <div class="box box-primary direct-chat direct-chat-primary" style="border:4px solid #3c8dbc;">
                <div style="font-size:20px; text-align:center; font-weight:300px">
                    <strong>{{$domain}}</strong>
                </div>
                    <div class="box-header" style="min-height:240px; vertical-align:middle; text-align:center; border-bottom:1px solid #3c8dbc; background: #3c8dbc url('http://wmse.s3-website.eu-central-1.amazonaws.com/assets/img/nbi.jpg') no-repeat center top/cover;padding-top: 30px;overflow: hidden;">
                        <p style="font-size: 30px; line-height: 40px; text-transform:UPPERCASE;color:#fff; ">HELLO, CUSTOMER-{{$user->id}}</p>
                       <span style="font-size: 18px; line-height: 40px; color:#00ff04; border-bottom:#00ff04 solid">Thank you for choosing us</span>
                    </div>
                    <div class="box-body"style="border-bottom:2px solid #3c8dbc;">
                        <p style="Margin:0;Margin-bottom:10px;color:#6a6a6a;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:700;line-height:1.3em;margin:0;margin-bottom:0;padding:0;text-align:center">Dear Customer</p>
                        <hr>
                        <h4>Your paper {{$order->order_no}} is now Active</h4>
                        You are now able to communicate with the writer via the <strong>ORDER MESSAGES SECTION</strong>. <br />
                        ALWAYS ASK YOUR WRITER FOR THE ASSIGNMENT UPDATES SO THAT YOU CAN BE ABLE TO MONITOR AS THE WORK IS BEING DONE!
                        <p>
                        </br>
                            The writer will upload your assignment so that you can be able to check and monitor the progress of your work.
                            Once the paper is completed, Donwload 100% completed paper and leave feedback.
                        </p>

                        <hr/>
                        <p style="text-align: center;"><span><a href="app.{{$domain}}/dashboard" style="Margin:0;border:0 solid #67c079;border-radius:5px;color:#fff;display:inline-block;font-family:Helvetica,Arial,sans-serif;font-size:24px;font-weight:400;line-height:1.3em;margin:0;padding:10px 17px 10px 17px;text-align:center;text-decoration:none;background:#3c8dbc;">My Orders</a></span><br>
                            <span style="display:inline-block;font-weight:700;line-height:50px;vertical-align:middle;">Thank you! </span><br>for  choosing {{$domain}} as your Custom paper provider <br/> We are glad to tell you more! Just contact our Support</p>
                            <hr/>
                            <div class="card-body small">
                                You are the one who decides which parts of your paper, are completed, and thus, require no further revisions.
You can follow up on the progress and/or chat with your writer to check how much work has been done on your order.
It`s important since the writer will be making revisions and gives you 100% quality work. Once you have accepted the paper it is no longer refundable.
Note: using {{$domain}}'s freelance board is completely secure and confidential. Your profile, orders or any other data you share with us cannot be viewed by other clients.
                            </div>
                    </div>
                    <div class="box-footer small">
                        &copy; <?php echo date("Y"); ?>, {{$domain}} <br>
                        This message was created automatically by our email system, please do not reply.<br>
                        You have received this e-mail because you are subscribed as a registered user of our service {{$domain}}.<br /> <a href="https://{{$domain}}/unsubscribe">Unsubscribe from emails</a>, if you don't want to receive messages from our mailing system anymore.<br>
                    </div>
            </div>
        </section>
            </div>
        </div>
    </body>
</html>