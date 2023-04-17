@extends('main.main')
@section('content')
@include('main.topbar')

<div class="row mt-3">
    <div class="col-md-12">
        <div class="card card-custom">
            <div class="row">
                <div class="col-md-6">
                    <p class="title-text pl-2">Add Role</p>
                </div>
            </div>
            <hr class="hr-custom">
            <div class="row">
                <div class="col-md-12">
                    <form action="{{route('post.add.role')}}" class="form-custom form stacked" method="POST"
                        id="formAddRole" ajax="true">
                        <div class="row p-0">
                            <div class="col-md-6 mt-2">
                                <div class="form-group">
                                    <label for="">Title</label>
                                    <div class="with-error">
                                        <input type="text" name="titleRole" id="titleRole" class="form-control"
                                            placeholder="Masukkan Title Role">
                                        <img src="{{asset('image/icon-error-form.svg')}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row p-0">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Permission</label>
                                </div>
                            </div>
                            <div id="accordion" class="col-md-12">
                                @for($i = 0; $i < count($permission); $i++) 
                                <div class="card">
                                    <div class="card-header" id="heading{{$i}}">
                                        <h5 class="mb-0">
                                            <button type="button" class="btn btn-link" data-toggle="collapse"
                                                data-target="#{{$permission[$i]['title']}}" aria-expanded="true"
                                                aria-controls="{{$permission[$i]['title']}}">
                                                {{ucwords(str_replace('_', ' ', $permission[$i]['title']))}}
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="{{$permission[$i]['title']}}" class="collapse show" aria-labelledby="headingOne"
                                        data-parent="#accordion">
                                        <div class="card-body">
                                            <div class="row">
                                                @for ($j = 0; $j < count($permission[$i]['child']); $j++)    
                                                <div class="col-md-12 p-0 mb-2">
                                                    <label for="permission_{{$permission[$i]['child'][$j]['title']}}" class="checkbox pl-0 mb-2">
                                                        <div class="row">
                                                            <div class="col-md-1 pr-0">
                                                                <center>
                                                                    <input class="fitur-role" name="permission" id="permission_{{$permission[$i]['child'][$j]['title']}}" type="checkbox" value="{{$permission[$i]['child'][$j]['id']}}" checked>
                                                                    <span class="cbver1"></span>
                                                                </center>
                                                            </div>
                                                            <div class="col-md-6 pl-0">
                                                                <p class="text-fitur"> {{ucwords(str_replace('_', ' ', $permission[$i]['child'][$j]['title']))}}</p>
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                 </div>
                                @endfor
                            </div>
                    </div>
                <hr class="hr-card">
                <div class="row mb-3">
                    <div class="col-md-12">
                        <button type="button" id="btnResetAddRole" class="btn-warning">Reset</button>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <button type="button" id="btnAddRole" class="btn-next">Add Role</button>
                        <button type="button" class="btn-back"><a href="{{route('get.role')}}">Kembali</a></button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>

@endsection
@section('modal')
<div class="modal fade" id="modalAddRole" aria-labelledby="modalAddRoleLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-custom">
        <div class="modal-content content-footer">
            <div class="modal-body">
                <center>
                    <img src="{{asset('image/icon-add-customer.svg')}}" class="mt-3">
                    <p class="title-notif" id="titleNotif">Kamu akan menambahkan Role ?</p>
                    <p class="content-notif" id="contentNotif">Kamu akan menambah Role, <br> apakah kamu yakin ?</p>
                </center>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-md-12" id="btnNotif">
                        <form action="{{route('post.add.role')}}" class="form-custom form stacked" method="POST"
                            id="formAddRole" ajax="true">
                            <button type="button" data-dismiss="modal" aria-label="Close" class="btn-notif-cancel"
                                id="">Batalkan</button>
                            <button type="submit" class="btn-notif">Lanjutkan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection