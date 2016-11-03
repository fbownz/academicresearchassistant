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
                    Congratulations, you have been assigned Order <b> #{{$order->order_no}}.</b> <br>

                </div>
                <div class="box-body">
                    <p>
                    	<a href="http://academicresearchassistants.com/orders/{{$order->id}}"> View your Order</a><br>
                    	Please make deliberate efforts to deliver a high quality paper that is 100% original and free of plagiarism by {{$deadline}}, <br> <br> 
						Failure to which you will be penalized accordingly. <a href="http://academicresearchassistants.com/fines-policy/">View our fines Policy.</a> 
						<br/><br> 
						Note that your remuneration will be reflected in your account only after your order has been approved by the quality assurance department. 
						</b>
                    </p>
                
                    
                </div>
                <div class="box-footer">
                    Regards <br>
                    AcademicResearchAssistants <br>
                    http://academicresearchassistants.com <br>
                    support@academicresearchassistatnts.com <br>
                </div>
            </div>
        </section>

    </body>
</html>