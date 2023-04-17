@extends('main.main')
@section('content')
@include('main.topbar')

<div class="row mt-3">
  <div class="col-md-12">
    <div class="card card-custom">
      <div class="row">
        <div class="col-md-6">
          <p class="title-text pl-2">List Transaksi</p>
        </div>
      </div>
      <hr class="hr-custom">
      <div class="row">
        <div class="col-md-12 pl-4 pr-4 table-responsive">
          <table id="tableTransaction" class="table-custom table table-strip stripe hover">
            <thead>
              <tr>
                <th class="text-center align-middle">ID Transaksi</th>
                <th class="text-center align-middle">No Invoice</th>
                <th class="text-center align-middle">Nama Customer</th>
                <th class="text-center align-middle">Whatsapp Customer</th>
                <th class="text-center align-middle">Tanggal Transaksi</th>
                <th class="text-center align-middle">Total</th>
                <th class="text-center align-middle">Status</th>
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
{{-- Status --}}
<div class="modal fade" id="modalChangeStatus" aria-labelledby="modalChangeStatusLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-custom">
    <div class="modal-content content-footer">
      <div class="modal-body">
        <center>
          <img src="{{asset('image/icon-change-status.svg')}}" class="mt-3">
          <p class="title-notif" id="titleNotif">Status dirubah menjadi “<span class="statusText"></span>” ?</p>
          <p class="content-notif" id="contentNotif">Kamu akan merubah status transaksi dari <span id="from"></span>
            menjadi <br> <span class="statusText" id="to"></span>, apakah kamu yakin ?</p>
        </center>
      </div>
      <div class="modal-footer">
        <div class="row">
          <div class="col-md-12" id="btnNotif">
            <form action="{{route('post.status.transaction')}}" class="form-custom form stacked" method="POST"
              id="formChangeStatusTransaction" ajax="true">
              <input type="hidden" name="idTransaction" id="idTransactionStatus" value="">
              <input type="hidden" name="statusChange" id="statusChange">
              {{-- FOR Pesanan Dikirim --}}
              <div class="row d-none" id="formPesananDikirim">
                <div class="col-md-12 mt-2" id="formPesananDikirimType">
                  <div class="form-group">
                    <label for="">Jenis Pengiriman</label>
                    <div class="col-md-12">
                      <select required style="width: 100%" name="deliveredType" id="deliveredType" class="form-select status-active">
                        <option value="" selected>Jenis Pengiriman</option>
                        <option value="kurir">Kurir Akomart</option>
                        <option value="ojol">Ojek Online</option>
                        <option value="pickup">Pickup</option>
                      </select>
                    </div>
                  </div>
                </div>

                <div class="col-md-12 mt-2" id="formPostmanName">
                  <div class="form-group">
                    <label for="">Nama Pengirim</label>
                    <div class="col-md-12">
                      <div class="with-error">
                        <input required type="text" name="postmanName" id="postmanName" class="form-control">
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              {{-- FOR Pesanan Diterima --}}
              <div class="row justify-content-center d-none" id="formPesananDiterima">
                <div class="col-md-12 mt-2" id="formReceiverName">
                  <div class="form-group">
                    <label for="">Nama Penerima</label>
                    <div class="col-md-12">
                      <div class="with-error">
                        <input type="text" name="receiverName" id="receiverName" class="form-control">
                      </div>
                    </div>
                  </div>
                </div>
              </div>


              <button type="button" data-dismiss="modal" aria-label="Close" class="btn-notif-cancel"
                id="">Batalkan</button>
              <button type="button" id="buttonStatusTransaction" class="btn-notif">Lanjutkan</button>
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
  function openModalStatus(status, idTransaction, uniqueNumber) {
      $("#formPesananDikirim").addClass('d-none');
      $("#formPesananDiterima").addClass('d-none');

        const statusTrans = {
          '0' : 'Menunggu Pembayaran',
          '1' : 'Pembayaran COD',
          '2' : 'Konfirmasi',
          '3' : 'Pesanan Diproses',
          '4' : 'Kirim Pesanan',
          '5' : 'Pesanan Selesai',
          '6' : 'Pesanan Dibatalkan',
        };
        var statusSelected = $(`#flagStatus-${uniqueNumber} option:selected`).val();
        if (statusSelected == '4') {
          $("#formPesananDikirim").removeClass('d-none');
        }

        if (statusSelected == '5') {
          $("#formPesananDiterima").removeClass('d-none');
        }
        $("#from").text(statusTrans[status]);
        $("#to").text(statusTrans[statusSelected]);
        $("#statusChange").val(statusSelected);

        $('.statusText').text(statusTrans[statusSelected])
        $("#idTransactionStatus").val(idTransaction);
        $("#modalChangeStatus").modal('show');
    }
</script>
@endsection