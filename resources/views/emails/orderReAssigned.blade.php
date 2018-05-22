<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="stylesheet" href="https://academicresearchassistants.com//css/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="http://academicresearchassistants.com//css/AdminLTE.min.css">
        <link rel="stylesheet" href="https://academicresearchassistants.com//css/skins/skin-green.min.css">

    </head>
    <body class="skin-green">
        <section class="content">
            <div style="font-size:20px; text-align:left; font-weight:300">
                <b>Academic</b>ResearchAssistants
            </div>
            <div class="box box-success">
                <div class="box-header no-border">
                    <h4>Greetings {{$user->first_name}},</h4>
                    Your order {{$order->order_no}} has been <b>Re-assigned.</b> <br>

                </div>
                <div class="box-body">
                    <p>
                    	This means that you should stop any further work on the paper.
                        An order will be re-assigned, if;
                        <ol>
                            <li>You request the paper to be re-assigned</li>
                            <li>You haven't responded to order messages.</li>
                            <li>Order is past its deadline and you haven't shown any deliberate effort to complete it.</li>
                            <li>You haven't responded to Revision requests on a delivered paper</li>
                            <li>The client has requested a new writer, maybe you didn't deliver the paper as requested.</li>
                            <li>or your account is terminated</li>
                        </ol> 
                        This also means that the order will not reflect on your remuneration.
						<a href="https://academicresearchassistants.com/fines-policy/">View our fines Policy.</a> 
						<br/><br> 
						Get in touch if you have any further questions or clarrification.
						</b>
                    </p>
                
                    
                </div>
                <div class="box-footer">
                    Regards <br>
                    AcademicResearchAssistants <br>
                    +254788882000 <br>
                    https://academicresearchassistants.com <br>
                    support@academicresearchassistatnts.com <br>
                </div>
            </div>
        </section>

    </body>
</html>