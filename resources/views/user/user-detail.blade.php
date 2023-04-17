@extends('main.main')
@section('content')
@include('main.topbar')

<div class="row mt-3">
    <div class="col-md-12">
        <div class="card card-custom">
            <div class="row">
                <div class="col-md-6">
                    <p class="title-text pl-2">Detail User</p>
                </div>
            </div>
            <hr class="hr-custom">
            <div class="row">
                <div class="col-md-12">
                    <form action="{{route('post.edit.user')}}" class="form-custom form stacked" method="POST" id="formEditUser" ajax="true">
                        <input readonly disabled type="hidden" name="idUser" value="{{$user->id}}">
                        <input readonly disabled type="hidden" name="oldStatus" value="{{$user->status}}">
                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-2 col-form-label">Name</label>
                            <div class="col-sm-10">
                              <input type="text" readonly class="form-control-plaintext" value=": {{$admin->name}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-2 col-form-label">Email</label>
                            <div class="col-sm-10">
                              <input type="text" readonly class="form-control-plaintext" value=": {{$user->email}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-2 col-form-label">No Telepon</label>
                            <div class="col-sm-10">
                              <input type="text" readonly class="form-control-plaintext" value=": {{$user->phone_number != null ? $user->phone_number : 'Not Have Phone Number'}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-2 col-form-label">Alamat</label>
                            <div class="col-sm-10">
                              <input type="text" readonly class="form-control-plaintext" value=": {{$user->address != null ? $user->address : 'Not Have An Address'}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-2 col-form-label">Role</label>
                            <div class="col-sm-10">
                              <input type="text" readonly class="form-control-plaintext" value=": {{ucwords($user->getRoleNameAttribute())}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-2 col-form-label">Status</label>
                            <div class="col-sm-10">
                              <input type="text" readonly class="form-control-plaintext" value=": {{$user->status ? 'Aktif' : 'Tidak Aktif'}}">
                            </div>
                        </div>

                        @if (canAccess('user_management_edit'))
                            <button type="button" class="btn btn-reset ml-3 mr-4 mb-3"><a class="click-edit" href="{{route('get.edit-detail.user', $user->id)}}">Edit</a></button>
                        @endif
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
