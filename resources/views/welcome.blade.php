@extends('main.main')
@section('content')
@include('main.topbar')

@if(session('session_id.type') == '1')
    @if(in_array('dashboard', session('session_id.permission')))

    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card card-custom">
                <div class="row">
                    <div class="col-md-6">
                        <p class="title-text pl-2 mb-3">Dashboard</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif


@endif

@endsection

