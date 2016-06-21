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
                    <h4>Verify Your Email Address</h4>
                   

                </div>
                <div class="box-body">
                    Hello {{$user->first_name}} {{$user->last_name}} <br><br><br>

                    Thanks for creating an account with the Academic Research Assistants. <br><br>
                    <hr>
                    <h4>Your login details:<h4>
                    Your Email:: {{$user->email}}<br>
                    Your Password:: The password you entered on the registration form<br><br>
                    <hr>
                    Please follow the link below to verify your email address<br>
                    {{ URL::to('register/verify/' . $confirmation_code) }}.<br/><br>
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