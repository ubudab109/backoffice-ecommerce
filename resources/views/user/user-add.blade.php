@extends('main.main')
@section('content')
@include('main.topbar')

<div class="row mt-3">
    <div class="col-md-12">
        <div class="card card-custom">
            <div class="row">
                <div class="col-md-6">
                    <p class="title-text pl-2">Add User</p>
                </div>
            </div>
            <hr class="hr-custom">
            <div class="row">
                <div class="col-md-12">
                    <form action="{{route('post.add.user')}}" class="form-custom form stacked" method="POST"
                        id="formAddUser" ajax="true">

                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Nama*</label>
                                <div class="col-sm-5">
                                    <input type="text" name="name" id="name" class="form-control" placeholder="Masukkan Nama User">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Email*</label>
                                <div class="col-sm-5">
                                    <input type="text"  name="email" id="email" class="form-control" placeholder="Masukkan Email User">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-2 col-form-label">No Telepon*</label>
                                <div class="col-sm-5">
                                    <div class="with-error">
                                        <input type="text" name="phoneNumber" id="phoneNumber" class="form-control"
                                        placeholder="Masukkan Nomor Telepon User">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Alamat*</label>
                                <div class="col-sm-5">
                                    <div class="with-error">
                                        <textarea class="form-control" placeholder="Masukkan Alamat User" name="address"
                                            id="address"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Role*</label>
                                <div class="col-sm-5">
                                    <div class="with-error">
                                        <div class="with-error">
                                            <select name="role" id="role" class="form-control select2">
                                                <option value="">Pilih Role</option>
                                                @foreach($role as $listRole)
                                                <option value="{{$listRole['id']}}">{{ucwords($listRole['title'])}}</option>
                                                @endforeach
                                            </select>
                                            <img src="{{asset('image/icon-error-form.svg')}}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Status</label>
                                <div class='custom-control custom-switch ml-3'>
                                    <input type='checkbox' name="status" checked class='custom-control-input' id='customSwitches-$row->id'>
                                    <label class='custom-control-label' for='customSwitches-$row->id'></label>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <button type="button" id="btnResetAddUser" class="btn btn-reset ml-2 mr-4">Reset</button>
                                <button type="button" id="btnAddUser" class="btn btn-save">Simpan</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('modal')
<div class="modal fade" data-backdrop="static" id="modalAddUser" aria-labelledby="modalAddUserLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-custom">
      <div class="modal-content content-footer-status">
        <div class="modal-body">
          <center>
            <p class="title-notif" id="titleNotif">Anda yakin ingin menambah data user ?</p>
          </center>
          <center>
            <div class="row">
              <div class="col-md-12" id="btnNotif">
                <form action="{{route('post.add.user')}}" class="form-custom form stacked" method="POST"
                    id="formAddUser" ajax="true">
                    <button class="btn btn-success btn-status" type="submit" class="btn-notif">Yes</button>
                    <button class="btn btn-danger btn-status cancel-status" type="button" data-dismiss="modal" aria-label="Close" class="btn-notif-cancel"
                        id="">Cancel</button>
                </form>
              </div>
            </div>
          </center>
        </div>
      </div>
    </div>
  </div>

{{-- <div class="modal fade" id="modalAddUser" aria-labelledby="modalAddUserLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-custom">
        <div class="modal-content content-footer">
            <div class="modal-body">
                <center>
                    <img src="{{asset('image/icon-add-customer.svg')}}" class="mt-3">
                    <p class="title-notif" id="titleNotif">Kamu akan menambahkan User ?</p>
                    <p class="content-notif" id="contentNotif">Kamu akan menambah User, <br> apakah kamu yakin ?</p>
                </center>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-md-12" id="btnNotif">
                        <form action="{{route('post.add.user')}}" class="form-custom form stacked" method="POST"
                            id="formAddUser" ajax="true">
                            <button type="button" data-dismiss="modal" aria-label="Close" class="btn-notif-cancel"
                                id="">Batalkan</button>
                            <button type="submit" class="btn-notif">Lanjutkan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> --}}
@endsection