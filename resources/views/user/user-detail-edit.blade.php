@extends('main.main')
@section('content')
@include('main.topbar')

<div class="row mt-3">
  <div class="col-md-12">
    <div class="card card-custom">
      <div class="row">
        <div class="col-md-6">
          <p class="title-text pl-2">Edit User</p>
        </div>
      </div>
      <hr class="hr-custom">
      <div class="row">
        <div class="col-md-12">
          <form action="{{route('post.edit.user')}}" class="form-custom form stacked" method="POST" id="formEditUser"
            ajax="true">
            <input type="hidden" name="idUser" value="{{$user->id}}">
            <input type="hidden" name="oldStatus" value="{{$user->status}}">

            <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Nama*</label>
                <div class="col-sm-5">
                    <input type="text" name="name" id="name" class="form-control" value="{{$admin->name}}" placeholder="Masukkan Nama User">
                </div>
            </div>

            <div class="form-group row">
              <label for="staticEmail" class="col-sm-2 col-form-label">Email*</label>
              <div class="col-sm-5">
                  <input type="text" name="email" id="email" class="form-control" value="{{$user->email}}" placeholder="Masukkan Email User">
              </div>
            </div>

            <div class="form-group row">
              <label for="staticEmail" class="col-sm-2 col-form-label">No Telepon*</label>
              <div class="col-sm-5">
                  <input type="text" name="phoneNumber" id="phoneNumber" class="form-control" value="{{$user->phone_number}}" placeholder="Masukkan Nomor Telepon User">
              </div>
            </div>

            <div class="form-group row">
              <label for="staticEmail" class="col-sm-2 col-form-label">Alamat*</label>
              <div class="col-sm-5">
                <textarea class="form-control" placeholder="Masukkan Alamat User" name="address"
                id="address">{{$user->address != null ? $user->address : 'Not Have An Address'}}</textarea>
              </div>
            </div>

            <div class="form-group row">
              <label for="staticEmail" class="col-sm-2 col-form-label">Role*</label>
              <div class="col-sm-5">
                <select name="role" id="role" class="form-control select2">
                  <option value="">Pilih Role</option>
                  @foreach($role as $listRole)
                  <option {{$admin->role_id == $listRole['id'] ? 'selected' : ''}}
                    value="{{$listRole['id']}}">{{ucwords($listRole['title'])}}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="form-group row">
              <label for="staticEmail" class="col-sm-2 col-form-label">Status</label>
              <div class="col-sm-5">
                @php
                if($user->status == true){
                  $active = 'checked';
                  $text = 'active';
                }else{
                  $active = '';
                  $text = 'inactive';
                }
                @endphp
                <div class='custom-control custom-switch ml-3'>
                  <input type='checkbox' name="flagStatus" {{$active}} class='custom-control-input' id='customSwitches-$row->id'>
                  <label class='custom-control-label' for='customSwitches-$row->id'></label>
                </div>
              </div>
            </div>
            <div class="row mb-3">
                <button type="button" id="btnResetEditUser" class="btn btn-reset ml-2 mr-4">Reset</button>
                <button type="button" id="btnEditUser" class="btn btn-save">Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
@section('modal')
<div class="modal fade" id="modalEditUser" aria-labelledby="modalEditUserLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-custom">
    <div class="modal-content content-footer">
      <div class="modal-body">
        <center>
          <img src="{{asset('image/icon-edit-customer.svg')}}" class="mt-3">
          <p class="title-notif" id="titleNotif">Edit data tersebut ?</p>
          <p class="content-notif" id="contentNotif">Kamu akan merubah data tersebut dan data akan <br> tergantikan
            dengan yang baru, apakah kamu yakin ?</p>
        </center>
      </div>
      <div class="modal-footer">
        <div class="row">
          <div class="col-md-12" id="btnNotif">
            <form action="{{route('post.edit.user')}}" class="form-custom form stacked" method="POST" id="formEditUser"
              ajax="true">
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

<div class="modal fade" id="modalChangeStatus" aria-labelledby="modalChangeStatusLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-custom">
    <div class="modal-content content-footer">
      <div class="modal-body">
        <center>
          <img src="{{asset('image/icon-change-status.svg')}}" class="mt-3">
          <p class="title-notif" id="titleNotif">Status dirubah menjadi “<span class="statusText"></span>” ?</p>
          <p class="content-notif" id="contentNotif">Kamu akan merubah status dari {{$text}} menjadi <br> <span
              class="statusText"></span>, apakah kamu yakin ?</p>
        </center>
      </div>
      <div class="modal-footer">
        <div class="row">
          <div class="col-md-12" id="btnNotif">
            <form action="{{route('post.delete.user')}}" class="form-custom form stacked" method="POST"
              id="formDeleteUser" ajax="true">
              <input type="hidden" name="idDeleteUser" value="{{$user->id}}">
              <input type="hidden" name="statusChange" id="statusChange">
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