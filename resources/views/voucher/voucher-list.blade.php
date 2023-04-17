@extends('main.main')
@section('content')
@include('main.topbar')

<div class="row mt-3">
    <div class="col-md-12">
        <div class="card card-custom">
            <div class="row">
                <div class="col-md-6">
                    <p class="title-text pl-2">List Voucher</p>
                </div>
                @if (canAccess('voucher_management_add'))
                <div class="col-md-6 pr-4">
                    <a href="{{route('add.voucher')}}"><img width="30" class="mt-4 right"
                            src="{{asset('image/add.png')}}"></a>
                </div>
                @endif
            </div>
            <hr class="hr-custom">
            <div class="row">
                <div class="col-md-12 pl-4 pr-4 table-responsive">
                    <table id="tableVoucher" class="table-custom table table-strip stripe hover">
                        <thead>
                            <tr>
                                <th class="text-center align-middle">Judul Voucher</th>
                                <th class="text-center align-middle">Kode Voucher</th>
                                <th class="text-center align-middle">Jenis Voucher</th>
                                <th class="text-center align-middle">Sisa Voucher</th>
                                <th class="text-center align-middle">Voucher Terpakai</th>
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
<div class="modal fade" id="modalChangeStatus" aria-labelledby="modalChangeStatusLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-custom">
        <div class="modal-content content-footer-status">
            <div class="modal-body">
                <center>
                    <p class="title-notif" id="titleNotif">Merubah Status Aktif atau Tidak Aktif ?</p>
                </center>
                <center>
                    <div class="row">
                        <div class="col-md-12" id="btnNotif">
                            <form action="{{route('post.status.voucher')}}" class="form-custom form stacked" method="POST"
                                id="formChangeStatusVoucher" ajax="true">
                                <input type="hidden" name="idVoucher" id="idVoucherStatus" value="">
                                <input type="hidden" name="statusChange" id="statusChange">

                                <div class="col-12">

                                    <button class="btn btn-success btn-status" type="button" id="buttonStatusVoucher">Yes</button>
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
<div class="modal fade" id="modalDeleteVoucher" aria-labelledby="modalDeleteLabel" aria-hidden="true">
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
                            <form action="{{route('post.delete.voucher')}}" class="form-custom form stacked" method="POST"
                                id="formDeleteVoucher" ajax="true">
                                <input type="hidden" name="idVoucher" id="idVoucherDelete" value="">
                                <div class="col-12">
                                    <button class="btn btn-success btn-status" type="button" id="deleteButtonVoucher">Yes</button>
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
@endsection
@section('script')
<script>
    function openModalDelete(idVoucher) {
            $("#idVoucherDelete").val(idVoucher);
            $("#modalDeleteVoucher").modal('show');
        }

        function openModalStatus(status, idVoucher) {
            if (status == '1') {
                $("#from").text('Active');
                $("#to").text('Inactive');
                $("#statusChange").val('0');
            } else {
                $("#from").text('Inactive');
                $("#to").text('Active');
                $("#statusChange").val('1');
            }
            $('.statusText').text(`${status == '1' ? 'Inactive' : 'Active'}`)
            $("#idVoucherStatus").val(idVoucher);
            $("#modalChangeStatus").modal('show');
        }
        
        function openIcon(src) {
            $("#modalIcon").modal('show');
            $("img#iconVoucher").attr('src', src);
        }
</script>
@endsection