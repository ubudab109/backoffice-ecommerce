<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; img-src 'self' data: https:; style-src 'self' 'unsafe-inline' https://fonts.gstatic.com; font-src 'self' https://fonts.gstatic.com; object-src 'none';" />
    <meta name="base" content="{{ URL::route('home') }}" />
    <meta name="baseImage" content="{{ url('storage') }}" />
    <meta name="csrf-token" content="{{csrf_token()}}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="{{asset('plugin/font/poppins.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('plugin/bootstrap-4.3.1/css/bootstrap.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('plugin/select2/dist/css/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('plugin/sweetalert/sweetalert2.min.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('plugin/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('main.css')}}">
    <link rel="apple-touch-icon" sizes="57x57" href="{{asset('favicon/apple-icon-57x57.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('favicon/favicon-32x32.png')}}">
    <link rel="shortcut icon" href="{{asset('favicon/favicon.ico')}}">       
    <title>Akomart Backoffice - Login</title>
    <meta name="description" content="Web administrasi Akomart. Login untuk melanjutkan">
</head>
<div id="loading-overlay">
    <div class="spinner">
        <div class="rect1"></div>
        <div class="rect2"></div>
        <div class="rect3"></div>
        <div class="rect4"></div>
        <div class="rect5"></div>
    </div>
</div>
<body>
    @php $notif = session('notif'); @endphp
    @if ($notif)
    <div id="notif" data-status="{!! $notif['status'] !!}" data-title="{!! $notif['title'] !!}" data-message="{!! $notif['message'] !!}" data-button="{!! $notif['button'] !!}">
    </div>
    @endif
    <div class="row row-full">
        <div class="col-md-5 p-0 col-left">
            <img src="{{asset('image/login/full.png')}}" class="img-bg">
            <img src="{{asset('image/login/Logo.svg')}}" class="img-logo" style="margin-left: 5%;
            margin-top: 50%;">
            <img src="{{asset('image/login/Mask group.png')}}" style="position: absolute; width: 72%; right: 0;">

        </div>
        <div class="col-md-7 col-right p-0">
            <div class="container p-5">
                <div class="row p-5">
                    <div class="col-md-12 pl-5">
                        <p class="content-login">Login To AKOmart App</p>
                    </div>
                    <div class="col-md-12 pl-5">
                        <form action="{{route('post.login')}}" class="form-custom form stacked" method="POST" id="formLogin" ajax="true">
                            <div class="row">
                                <div class="col-md-11 p-0 mt-2">
                                    <div class="form-group">
                                        <label for="">Email</label>
                                        <div class="with-error">
                                            <input type="text" name="username" id="username" class="form-control" placeholder="Masukkan email kamu">
                                            <img src="{{asset('image/icon-error-form.svg')}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-11 p-0 mt-2">
                                    <div class="form-group">
                                        <label for="">Password</label>
                                        <div class="with-icon with-error">
                                            <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password kamu">
                                            <p class="thisIconEye">Show Password</p>
                                            <img src="{{asset('image/icon-error-form.svg')}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-4 p-0">
                                    <div class="form-group captcha">
                                        <span>{!! captcha_img('flat') !!}</span>
                                        <button type="button" class="btn-reload" class="reload" id="reloadCaptcha">
                                            <img src="{{asset('image/login/icon-reload.svg')}}" class="icon-reload">
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-7 p-0">
                                    <div class="form-group">
                                        <div class="with-error">
                                            <input type="text" name="captchaLogin" id="captchaLogin" class="form-control" placeholder="Masukkan captcha disamping">
                                            <img src="{{asset('image/icon-error-form.svg')}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-7 p-0">
                                    <p class="text-footer">
                                        Lorem ipsum dolor sit amet, consectetur <br> adipiscing elit. <a href="">Terms Condition</a> or <a href="">Privacy Policy</a> 
                                    </p>
                                </div>
                                <div class="col-md-4 p-0">
                                    <button type="submit" class="btn-login w-100 disabled" disabled id="loginBtn">Login Now</button>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-5 p-0">
                                    <hr class="hr-login">
                                </div>
                                <div class="col-md-1 p-0">
                                    <center>
                                        <p class="footer-login mt-2">or</p>
                                    </center>
                                </div>
                                <div class="col-md-5 p-0">
                                    <hr class="hr-login">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-11 p-0">
                                    <center>
                                        <p class="footer-login">Lupa Password kamu ?&nbsp<a href="{{route('get.forget.password')}}" class="forget-password">Forget Password</a></p>
                                    </center>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-11 footer-bantuan">
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="modal fade" id="modalNotif" aria-labelledby="modalNotifLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-custom">
            <div class="modal-content content-footer">
                <div class="modal-body">
                    <center>
                        <img src="{{asset('image/icon-error.svg')}}" class="mt-3">
                        <p class="title-notif" id="titleNotif">Maaf terjadi kesalahan</p>
                        <p class="content-notif" id="contentNotif">Telah terjadi kesalahan pada sistem, periksa kembali <br> email dan password Anda</p>
                    </center>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-md-12" id="btnNotif">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script type="text/javascript" src="{{asset('plugin/jquery/jquery-3.6.0.min.js')}}"></script>
<script type="text/javascript" src="{{asset('plugin/bootstrap-4.3.1/js/bootstrap.min.js')}}"></script>
<script type="text/javascript" src="{{asset('plugin/crypto-js/crypto-js.js') }}"></script>
<script type="text/javascript" src="{{asset('plugin/jquery-validate/dist/jquery.validate.min.js') }}"></script>
<script type="text/javascript" src="{{asset('plugin/jquery-validate/dist/additional-methods.js') }}"></script>
<script type="text/javascript" src="{{asset('plugin/select2/dist/js/select2.full.min.js')}}"></script>
<script type="text/javascript" src="{{asset('plugin/sweetalert/sweetalert2.min.js')}}"></script>
<script type="text/javascript" src="{{asset('plugin/mask/jquery.mask.min.js')}}"></script>
<script type="text/javascript" src="{{asset('plugin/summernote/summernote-lite.min.js')}}"></script>
<script type="text/javascript" src="{{asset('plugin/moment/moment.js')}}"></script>
<script type="text/javascript" src="{{asset('plugin/chartjs/chart.min.js')}}"></script>
<script type="text/javascript" src="{{asset('plugin/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js')}}"></script>
<script type="text/javascript" src="{{asset('main.js')}}"></script>
</html>
