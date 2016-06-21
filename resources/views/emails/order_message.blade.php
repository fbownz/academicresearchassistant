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
                    <h4>Greetings {{$user->first_name}},,</h4>
                    You have received a new message relating to Order #{{$order->order_no}}<br>

                </div>
                <div class="box-body">
				Please make sure that you take into account any new instructions while completing the assignment. 
				<a href="http://academicresearchassistants.com/orders/{{$order->id}}#order-message"> View your Message</a>
				</p>
				<hr>
				<p>
				Remember that the complete product for <b>Order #{{$order->order_no}} should be delivered by {{$order->deadline}}.</b> 
				Please make deliberate efforts to complete the order in a timely manner. <br/>
                
                    
                </div>
                <div class="box-footer">
                    Regards <br>
                    AcademicResearchAssistants <br>
                    http://academicresearchassistants.com <br>
                    info@academicresearchassistatnts.com <br>
                </div>
            </div>
        </section>

    </body>
</html>