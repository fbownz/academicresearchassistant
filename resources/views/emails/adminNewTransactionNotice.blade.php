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
                    <h4>Order {{$order->order_no}} has been paid!</h4>

                </div>
                <div class="box-body">
                   Hello Admin<br><br>

                A summary of the transaction details.<br><br>
                <strong>Order ID::</strong> <a href="http://academicresearchassistants.com/orders/{{$order->id}}">View order {{$order->order_no}}</a><br>
                <strong>InvoiceID::</strong> {{$transaction->transactionid}}<br>
                <strong>Response Code::</strong> {{$transaction->responseCode}}<br>
                <strong>Price::</strong> {{$transaction->price}}<br>
                <strong>Total::</strong> {{$transaction->total}}<br>
                <strong>Payment Method::</strong> {{$transaction->pay_method}}<br>
                <strong>Coupon::</strong> {{$transaction->coupon}}<br>
                <strong>First Name::</strong> {{$client->first_name}}<br>
                <strong>Last Name::</strong> {{$client->last_name}}<br>
                <strong>Address1::</strong> {{$transaction->street_address}}<br>
                <strong>Address2::</strong> {{$transaction->street_address2}}<br>
                <strong>City:</strong> {{$transaction->city}}<strong> State:</strong> {{$transaction->address_state}}<br>
                <strong>Country::</strong> {{$transaction->country}}<br>
                <strong>Phone::</strong> {{$transaction->phone}}<br>
                <strong>IP Country::</strong> {{$transaction->ip_country}}<br>

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