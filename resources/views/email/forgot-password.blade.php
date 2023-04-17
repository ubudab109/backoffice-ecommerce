@include('email.header')
{{-- <h3>Hello, {{isset($dataUser) ? $dataUser->name}}</h3> --}}
<p>
    You are receiving this email because we received a password reset request for your account.
    Please use the code below to reset your password.
</p>
<p style="text-align:center;font-size:30px;font-weight:bold">
    {{$token}}
</p>
<p>You can change your password with this link :: <a href="{{route('new.password',$token)}}">Click Here</a></p>

<p>
    {{__('Thanks a lot for being with us.')}} <br/>
    {{config('app.name')}}
</p>