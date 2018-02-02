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
                    As one of our Topwriters in your fields, <b>Customer {{$order->client_ID}}</b> has requested you to complete their Paper. <br>
                    Note that the bid compensation is competitive as compared to other bids and other selected top writers have also been requested to complete the paper. <br>
                   <span style="text-transform: uppercase;"> <strong>Kindly go through the order details on ARA and only accept this request if you are sure to produce 100% quality work on time.</strong></span>
                    <br />

                </div>
                <div class="box-body">
                    <p>
                    
						Click the link below to view the order and accept the request<br/>
                        <a href="http://academicresearchassistants.com/orders/{{$order->id}}"><b>Order #{{$order->order_no}}</b> </a><br><br>
						<hr>
                        <span class="small">
                         <h4>
                            You are now able to chat directly with customers 
                        </h4>
                        Any message you post on the messages section will be seen by both the admins and the clients.<br/>
                        We are monitoring <b>*ALL</b> communication you will have with the client.<br/>
                        You are not allowed to request personal/contact information from the client.<br/>
                        <b>Your account will be banned without pay if we notice any violation to the above agreements.</b> <br/>
                        Please get in touch with the Quality Assurance Department if you need any further assistance or clarification.<br>
                        <a href="http://academicresearchassistants.com/fines-policy">Have a look at our fines policy</a>
                    </span>
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