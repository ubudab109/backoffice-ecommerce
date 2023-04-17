<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Security-Policy"
        content="default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; img-src 'self' data: https:; style-src 'self' 'unsafe-inline' https://fonts.gstatic.com; font-src 'self' https://fonts.gstatic.com; object-src 'none'" />
    <meta charset="UTF-8">
    <meta name="base" content="{{ URL::route('home') }}" />
    <meta name="baseImage" content="{{ env('API_URI') }}" />
    <meta name="csrf-token" content="{{csrf_token()}}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="{{asset('plugin/font/font-awesome.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('plugin/font/poppins.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('plugin/summernote/summernote-bs4.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('plugin/bootstrap-4.3.1/css/bootstrap.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('plugin/select2/dist/css/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('plugin/sweetalert/sweetalert2.min.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('plugin/datatable/datatables.min.css')}}">
    <link rel="stylesheet" type="text/css"
        href="{{asset('plugin/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css')}}">
    <link rel="stylesheet" href="{{asset('plugin/bootstrap-select/bootstrap-select.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('plugin/image-picker-2/image-picker.css')}}">
    <link rel="stylesheet" href="{{asset('plugin/dropzone/dropzone.min.css')}}" type="text/css" />
    <link rel="stylesheet" type="text/css" href="{{asset('main.css')}}">
    <link rel="apple-touch-icon" sizes="57x57" href="{{asset('favicon/apple-icon-57x57.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('favicon/favicon-32x32.png')}}">
    <link rel="shortcut icon" href="{{asset('favicon/favicon.ico')}}">       
    <title>Akomart Backoffice</title>
    <meta name="description" content="Web administrasi Akomart">
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
    <div id="notif" data-status="{!! $notif['status'] !!}" data-message="{!! $notif['title'] !!}"
        data-message="{!! $notif['message'] !!}" data-url="{!! $notif['button'] !!}">
    </div>
    @endif
    <div class="wrapper">
        {{-- <div class="sidebar_expand d-none">
            <img src="{{asset('image/sidebar/sidebar_collapse.png')}}" alt="">
        </div>
        <div class="sidebar_collapse">
            <img src="{{asset('image/sidebar/collapse_sidebar.png')}}" alt="">
        </div> --}}
        @include('main.navbar')
        @include('main.sidebar')
        <div class="content-wrapper">
            @yield('content')
        </div>
        
        <div class="modal fade" data-backdrop="static" id="modalNotif" aria-labelledby="modalNotifLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-custom">
                <div class="modal-content content-footer-status">
                    <div class="modal-body">
                        <center>
                            {{-- <img src="{{asset('image/icon-error.svg')}}" class="mt-3 hidden" id="iconError">
                            <img src="{{asset('image/icon-success.svg')}}" class="mt-3 hidden" id="iconSuccess"> --}}
                            <p class="title-notif" id="titleNotif"></p>
                            <p class="content-notif" id="contentNotif"></p>
                        </center>
                        <center>
                            <div class="row">
                                <div class="col-md-12" id="btnNotif">
                                    
                                </div>
                            </div>
                        </center>
                    </div>
                </div>
            </div>
        </div>

        @section('modal')

        @show
    </div>
</body>
<script type="text/javascript" src="{{asset('plugin/jquery/jquery-3.6.0.min.js')}}"></script>
<script type="text/javascript" src="{{asset('plugin/popper/popper.min.js')}}"></script>
<script type="text/javascript" src="{{asset('plugin/datatable/datatables.min.js')}}"></script>
<script type="text/javascript" src="{{asset('plugin/datatable/pdfmake-0.1.36/pdfmake.min.js')}}"></script>
<script type="text/javascript" src="{{asset('plugin/datatable/pdfmake-0.1.36/vfs_fonts.js')}}"></script>
<script type="text/javascript" src="{{asset('plugin/datatable/Buttons-1.5.4/js/dataTables.buttons.min.js')}}"></script>
<script type="text/javascript" src="{{asset('plugin/datatable/JSZip-2.5.0/jszip.min.js')}}"></script>
<script type="text/javascript" src="{{asset('plugin/datatable/Buttons-1.5.4/js/buttons.html5.min.js')}}"></script>
<script type="text/javascript" src="{{asset('plugin/bootstrap-4.3.1/js/bootstrap.min.js')}}"></script>
<script type="text/javascript" src="{{asset('plugin/crypto-js/crypto-js.js') }}"></script>
<script type="text/javascript" src="{{asset('plugin/jquery-validate/dist/jquery.validate.js') }}"></script>
<script type="text/javascript" src="{{asset('plugin/jquery-validate/dist/additional-methods.js') }}"></script>
<script type="text/javascript" src="{{asset('plugin/select2/dist/js/select2.full.min.js')}}"></script>
<script type="text/javascript" src="{{asset('plugin/sweetalert/sweetalert2.min.js')}}"></script>
<script type="text/javascript" src="{{asset('plugin/multifile-master/jquery.MultiFile.min.js')}}"></script>
<script type="text/javascript" src="{{asset('plugin/mask/jquery.mask.min.js')}}"></script>
<script type="text/javascript" src="{{asset('plugin/moment/moment.js')}}"></script>
<script type="text/javascript" src="{{asset('plugin/apexcharts/apexcharts.min.js')}}"></script>
<script type="text/javascript" src="{{asset('plugin/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js')}}">
</script>
<script type="text/javascript" src="{{asset('plugin/summernote/summernote-bs4.js')}}"></script>
<script type="text/javascript" src="{{asset('plugin/dropzone/dropzone.min.js')}}"></script>
<script src="{{asset('plugin/bootstrap-select/bootstrap-select.min.js')}}"></script>
<script src="{{asset('main.js')}}"></script>

<script>
    $('.summernote').summernote({
        tabsize: 2,
        height: 100
    });
</script>
@yield('script')

</html>