<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="stylesheet" href="http://academicresearchassistants.com//css/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="http://academicresearchassistants.com//css/AdminLTE.min.css">
        <link rel="stylesheet" href="http://academicresearchassistants.com//css/skins/skin-green.min.css">

    </head>
    <body class="skin-green">
        <section class="content">
            <div style="font-size:20px; text-align:left; font-weight:300">
                <b>Academic</b>ResearchAssistants
            </div>
            <div class="box box-success">
                <div class="box-header no-border">
                    <h4>Greetings {{$user->first_name}},</h4>
                    We've received the {{$order->status}} paper for order #{{$order->order_no}}.<br>

                </div>
                <div class="box-body">
                    <p>
                    	<a href="http://academicresearchassistants.com/orders/{{$order->id}}#order_file"> View your Paper</a><br><br>
                    	The quality assurance department will review the paper and get in touch with you in case of any revisions. <br> 
                    	Please check your order status regularly for new updates. <br>
                    </p>
                
                    
                </div>
                <div class="box-footer">
                    Regards <br>
                    AcademicResearchAssistants <br>
                    http://academicresearchassistants.com <br>
                    qualityassurance@academicresearchassistatnts.com <br>
                </div>
            </div>
        </section>

    </body>
</html>