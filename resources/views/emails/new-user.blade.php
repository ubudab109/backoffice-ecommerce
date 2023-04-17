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
        <img src="https://akomart.id/logo-akomart.png" alt="">
        <p>GET STARTED</p>
        <p>Hi, {{$name}}</p>
        <p>Welcome to Akomart Backoffice. Your account has been successfully created.</p>
        <p>To login to your account, please find your detail below :</p>
        <p>Username : {{$username}}</p>
        <p>Password : {{$password}}</p>
        <p>Note that this is your default password, please reset your password when sign in to Akomart Backoffice </p>
        <a href="{{route('get.login.view')}}" style="text-decoration:none;"><button style="background-color:red; color:white; height:30px; width:150px; border-radius:25px;">Sign In</button></a>
        <br>
        <p>Please contact us if you have any difficulty login to your account.</p>
        <br>
        <p>Thank You,</p>
        <p>Backoffice / Akomart</p>
    </center>
</body>
</html>
