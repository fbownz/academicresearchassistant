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
                    <b>Order #{{$order->order_no}}</b> needs urgent revision <br>

                </div>
                <div class="box-body">
                    <p>
                    	<a href="http://academicresearchassistants.com/orders/{{$order->id}}"> View your Order</a><br>
                    	The revised product should be delivered by {{$order->deadline}}. <br>
						Please be advised that you will be penalized if the product does not conform to the revision instructions. <br> 
						Further penalties will also be imposed if you do not deliver the paper in a timely manner.
						<hr>
						Check our Fines policy belw:<br>
						<b><a href="http://academicresearchassistants.com/fines-policy">http://academicresearchassistants.com/fines-policy</a>
						<br/>
						<b>Your remuneration will be reflected in your account only after your order has been approved by the quality assurance department.
						</b>

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