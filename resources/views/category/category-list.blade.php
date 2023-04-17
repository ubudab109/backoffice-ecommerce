@extends('main.main')
@section('content')
@include('main.topbar')

<div class="row mt-3">
    <div class="col-md-12">
        <div class="card card-custom">
            <div class="row">
                <div class="col-md-6">
                    <p class="title-text pl-2">List Category</p>
                </div>
                @if (canAccess('category_management_add'))
                <div class="col-md-6 pr-4">
                    <a href="{{route('add.category')}}"><img width="30" class="mt-4 right"
                            src="{{asset('image/add.png')}}"></a>
                </div>
                @endif
            </div>
            <hr class="hr-custom">
            <div class="row">
                <div class="col-md-12 pl-4 pr-4 table-responsive">
                    <table id="tableCategory" class="table-custom table table-strip stripe hover">
                        <thead>
                            <tr>
                                <th class="text-center align-middle">Icon</th>
                                <th class="text-center align-middle">Creator</th>
                                <th class="text-center align-middle">Name</th>
                                <th class="text-center align-middle">Code</th>
                                <th class="text-center align-middle">Status</th>
                                <th class="text-center align-middle">Action</th>
                                <th class="text-center align-middle" style="visibility: hidden">Status</th>
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
{{-- Status --}}
<div class="modal fade" data-backdrop="static" id="modalChangeStatus" aria-labelledby="modalChangeStatusLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-custom">
        <div class="modal-content content-footer-status">
            <div class="modal-body">
                <center>
                    <p class="title-notif" id="titleNotif">Merubah Status Aktif atau Tidak Aktif ?</p>
                </center>
                <center>
                    <div class="row">
                        <div class="col-md-12" id="btnNotif">
                            <form action="{{route('post.status.category')}}" class="form-custom form stacked" method="POST"
                                id="formChangeStatusCategory" ajax="true">
                                <input type="hidden" name="idCategory" id="idCategoryStatus" value="">
                                <input type="hidden" name="statusChange" id="statusChange">

                                <div class="col-12">
                                    <button class="btn btn-success btn-status" type="button" id="buttonStatusCategory">Yes</button>
                                    <button class="btn btn-danger btn-status cancel-status" type="button" data-dismiss="modal" aria-label="Close"
                                        id="">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </center>
            </div>

        </div>
    </div>
</div>

{{-- DELETE --}}
<div class="modal fade" id="modalDeleteCategory" aria-labelledby="modalDeleteLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-custom">
        <div class="modal-content content-footer">
            <div class="modal-body">
                <center>
                    <img src="{{asset('/image/delete_modal.svg')}}" alt="">
                    <p class="title-notif" id="titleNotif">Anda Yakin Ingin Menghapus ?</p>
                </center>
                <center>
                    <div class="row">
                        <div class="col-md-12" id="btnNotif">
                            <form action="{{route('post.delete.category')}}" class="form-custom form stacked" method="POST"
                                id="formDeleteCategory" ajax="true">
                                <input type="hidden" name="idCategory" id="idCategoryDelete" value="">

                                <div class="col-12">

                                    <button class="btn btn-success btn-status" type="button" id="deleteButtonCategory">Yes</button>
                                    <button class="btn btn-danger btn-status" type="button" data-dismiss="modal" aria-label="Close"
                                      id="">Cancel</button>
                                  </div>
                            </form>
                        </div>
                    </div>
                </center>
            </div>
        </div>
    </div>
</div>

{{-- ICON --}}
<div class="modal fade" id="modalIcon" aria-labelledby="modalChangeStatusLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-custom">
        <div class="modal-content content-footer">
            <div class="modal-body">
                <center>
                    <img src="" id="iconCategory" class="mt-3" width="200">
                </center>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-md-12" id="btnNotif">
                        <button type="button" data-dismiss="modal" class="btn-notif">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    function openModalDelete(idCat) {
            $("#idCategoryDelete").val(idCat);
            $("#modalDeleteCategory").modal('show');
        }

        function openModalStatus(status, idCat) {
            if (status == '1') {
                $("#statusChange").val('0');
            } else {
                $("#statusChange").val('1');
            }
            $('.statusText').text(`${status == '1' ? 'Inactive' : 'Active'}`)
            $("#idCategoryStatus").val(idCat);
            $("#modalChangeStatus").modal('show');
        }
        
        function openIcon(src) {
            $("#modalIcon").modal('show');
            $("img#iconCategory").attr('src', src);
        }
</script>
@endsection