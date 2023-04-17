@include('email.header')
<h3>Hello, {{$dataUser}}</h3>
<p>
    You are receiving this email because You have been invited as a {{$role}}.
    Please change Your password first to access the application in this link.
</p>
<p>You can change your password with this link :: <a href="{{route('get.reset.password',$user)}}">Click Here</a></p>

<p>
    {{__('Thanks a lot for being with us.')}} <br/>
    {{config('app.name')}}
</p>