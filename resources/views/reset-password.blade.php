<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="base" content="{{ URL::route('home') }}" />
    <meta name="baseImage" content="{{ url('storage') }}" />
    <meta name="csrf-token" content="{{csrf_token()}}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('plugin/bootstrap-4.3.1/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugin/select2/dist/css/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('plugin/sweetalert/sweetalert2.min.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('plugin/datatable/datatables.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('plugin/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css')}}">
    <link rel="stylesheet" href="{{asset('main.css')}}">
    <link rel="apple-touch-icon" sizes="57x57" href="{{asset('favicon/apple-icon-57x57.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('favicon/favicon-32x32.png')}}">
    <link rel="shortcut icon" href="{{asset('favicon/favicon.ico')}}">       
    <title>Akomart Backoffice - Reset Password</title>
    <meta name="description" content="Web administrasi Akomart. Isi formulir untuk reset password">
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
    <div class="row row-full">
        <div class="col-md-5 p-0 col-left">
            <img src="{{asset('image/login/full.png')}}" class="img-bg" style="">
            <img src="{{asset('image/login/logo.svg')}}" class="img-logo" style="margin-left: 5%;
            margin-top: 50%;">
            <img src="{{asset('image/login/Mask Group.png')}}" style="position: absolute; width: 72%; right: 0;">

        </div>
        <div class="col-md-7 col-right p-0">
            <div class="container p-5">
                <div class="row p-5">
                    <div class="col-md-12 pl-5">
                        <p class="content-forget">Confirm New Password</p>
                    </div>
                    <div class="col-md-12 pl-5">
                        <form action="{{route('post.reset.password')}}" class="form-custom form stacked" method="POST" id="formResetPassword" ajax="true">
                            <input type="hidden" name="idUser" id="idUser" value="{{$user->id}}">
                            <div class="row">
                                <div class="col-md-11 p-0 mt-2">
                                    <div class="form-group">
                                        <label for="">New Password</label>
                                        <div class="with-icon with-error">
                                            <input type="password" name="newPassword" id="newPassword" class="form-control" placeholder="Masukkan password baru kamu">
                                            <p class="thisIconEye">Show Password</p>
                                            <img src="{{asset('image/icon-error-form.svg')}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-11 p-0 mt-2">
                                    <div class="form-group">
                                        <label for="">Confirmation Password</label>
                                        <div class="with-icon with-error">
                                            <input type="password" name="konfirmasiPassword" id="konfirmasiPassword" class="form-control" placeholder="Masukkan konfirmasi password baru kamu">
                                            <p class="thisIconEyeConf">Show Password</p>
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
                                <div class="col-md-7 p-0"></div>
                                    <div class="col-md-4 p-0">
                                        <button type="submit" class="btn-login w-100 disabled" disabled id="loginReset">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-11 footer-bantuan">
                    </div>
                </div>
                <div class="row p-5">
                </div>
            </div>

        </div>
    </div>
    <div class="modal fade" id="modalNotif" aria-labelledby="modalNotifLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-custom">
            <div class="modal-content content-footer">
                <div class="modal-body">
                    <center>
                        <img src="{{asset('image/icon-reset-password.svg')}}" class="mt-3">
                        <p class="title-notif" id="titleNotif"></p>
                        <p class="content-notif" id="contentNotif"></p>
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
<script type="text/javascript" src="{{asset('plugin/datatable/datatables.min.js')}}"></script>
<script src="{{asset('plugin/bootstrap-4.3.1/js/bootstrap.min.js')}}"></script>
<script type="text/javascript" src="{{asset('plugin/crypto-js/crypto-js.js') }}"></script>
<script type="text/javascript" src="{{asset('plugin/jquery-validate/dist/jquery.validate.min.js') }}"></script>
<script type="text/javascript" src="{{asset('plugin/jquery-validate/dist/additional-methods.js') }}"></script>
<script src="{{asset('plugin/select2/dist/js/select2.full.min.js')}}"></script>
<script src="{{asset('plugin/sweetalert/sweetalert2.min.js')}}"></script>
<script src="{{asset('plugin/mask/jquery.mask.min.js')}}"></script>
<script src="{{asset('plugin/summernote/summernote-lite.min.js')}}"></script>
<script src="{{asset('plugin/moment/moment.js')}}"></script>
<script src="{{asset('plugin/chartjs/chart.min.js')}}"></script>
<script src="{{asset('plugin/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js')}}"></script>
<script src="{{asset('main.js')}}"></script>
</html>
