@extends('main.main')
@section('content')
@include('main.topbar')

<div class="row mt-3">
    <div class="col-md-12">
        <div class="card card-custom">
            <div class="row">
                <div class="col-md-6">
                    <p class="title-text pl-2">List Role</p>
                </div>
                @if (canAccess('role_management_add'))
                <div class="col-md-6 pr-4">
                    <a href="{{route('add.role')}}"><img width="30" class="mt-4 right"
                            src="{{asset('image/add.png')}}"></a>
                </div>
                @endif
            </div>
            <hr class="hr-custom">
            <div class="row">
                <div class="col-md-12 pl-4 pr-4 table-responsive">
                    <table id="tableRole" class="table-custom table table-strip stripe hover">
                        <thead>
                            <tr>
                                <th class="text-center align-middle">Title</th>
                                <th class="text-center align-middle">Creator</th>
                                <th class="text-center align-middle">Updated At</th>
                                <th class="text-center align-middle">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('modal')
{{-- DELETE --}}
<div class="modal fade" id="modalDeleteRole" aria-labelledby="modalDeleteLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-custom">
        <div class="modal-content content-footer">
            <div class="modal-body">
                <center>
                    <img src="{{asset('image/icon-change-status.svg')}}" class="mt-3">
                    <p class="title-notif" id="titleNotif">Data Role ini akan dihapus ?</p>
                    <p class="content-notif" id="contentNotif">Kamu akan menghapus data role ini <br> <span
                            class="statusText"></span> apakah kamu yakin ?</p>
                </center>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-md-12" id="btnNotif">
                        <form action="{{route('post.delete.role')}}" class="form-custom form stacked" method="POST"
                            id="formDeleteRole" ajax="true">
                            <input type="hidden" name="idRole" id="idRoleDelete" value="">
                            <button type="button" data-dismiss="modal" aria-label="Close" class="btn-notif-cancel"
                                id="">Batalkan</button>
                            <button type="button" id="deleteButtonRole" class="btn-notif">Lanjutkan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    function openModalDelete(idRole) {
      $("#idRoleDelete").val(idRole.toString());
      $("#modalDeleteRole").modal('show');
    }
</script>
@endsection