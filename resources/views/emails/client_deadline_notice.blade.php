<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="stylesheet" href="http://academicresearchassistants.com//css/bootstrap/css/bootstrap.min.css">

    </head>
    <body class="skin-green">
        <section class="content">
            <div style="font-size:20px; text-align:left; font-weight:300">
                <b>Academic</b>ResearchAssistants
            </div>
            <div class="box box-success">
                <div class="box-header no-border">
                    <h4>Greetings {{$admin->first_name}},</h4>
                    The client deadline for Order <b>#{{$order->order_no}}</b> has elapsed.<br>


                </div>
                <div class="box-body">
                    <p>
                        Please follow this link to check whether the order was uploaded by the writer and delivered to the client successfully.
                        <a href="http://academicresearchassistants.com/orders/{{$order->id}}">Click this link to view the order</a><br><br>
                    </p>
                
                    
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