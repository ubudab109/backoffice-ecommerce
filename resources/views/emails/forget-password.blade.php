<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forget Password</title>
</head>
<body>
    <center>
        <img src="www.telkomsel.com/sites/default/files/mainlogo-2022-rev.png" alt="">
        <p>Reset Password</p>
        <p>Hi, {{$name}}</p>
        <p>You are receiving this email because you requested a password reset for your Akomart backoffice account.</p>
        <p>To proceed to your [backoffice] account, please click Reset Password button to change your password.</p>
        <a href="{{route('get.reset.password', $username)}}" target="_blank"><button style="background-color:red; color:white; height:30px; width:150px; border-radius:25px;">Reset Password</button></a>
        <br>
        <p> Please contact us if you have any difficulty login to your account.</p>
        <br>
        <p>Thank You,</p>
        <p>Akomart / [backoffice]</p>
    </center>
</body>
</html>
