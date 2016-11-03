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
                    This is to notify you that order <b>#{{$order->order_no}}</b> has been marked as late and thus you need to get in touch with the writer.<br>

                </div>
                <div class="box-body">
                    <p>
                    	In light of this, a 10% fine will be deducted from the total compensation for this order. <br>
						<a href="http://academicresearchassistants.com/orders/{{$order->id}}">Click this link to view the order</a><br><br>
						<a href="http://academicresearchassistants.com/fines-policy">Have a look at the fines policy</a>
						<hr>
						Please get in touch with the writer to make sure the order is delivered on time.
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