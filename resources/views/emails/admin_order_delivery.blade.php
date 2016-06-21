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
                    <h4>Greetings Admin,</h4>
                    {{$user->first_name}} has delivered a @if($order->status="Delivered") Final copy @else Draft copy @endif paper for order #{{$order->order_no}}<br>
                </div>
                <div class="box-body">
                    <a href="http://academicresearchassistants.com/orders/{{$order->id}}#order_file" class="btn btn-success"> View the Paper</a><br>
                    <b>Kindly review the paper and approve it, if changes are needed, kindly mark the order as "Active-Revision" and add write the writer a note on the message section.</b><br>
                    N/B the writer's earnings will be updated after you approve the order.

                    The writer will also get the order message 
                    
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
