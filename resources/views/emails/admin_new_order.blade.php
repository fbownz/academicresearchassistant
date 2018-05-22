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
                    <h4>A new User order {{$order->order_no}} has been successfully posted</h4>

                </div>
                <div class="box-body">
                   Hello Admin<br><br>

                A summary of the order details.<br><br>
                Type of Paper: {{$order->type_of_product}}<br>
                Subject: {{$order->subject}}<br>
                Number of pages: {{$order->word_length}}<br>
                Deadline: {{$order->deadline}}<br><br>
                <hr>
                <h5>Client Details</h5>
                UserID: {{$client->id}}<br>
                Name: {{$client->first_name}} {{$client->last_name}}<br>
                Email: {{$client->email}}<br><br>

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