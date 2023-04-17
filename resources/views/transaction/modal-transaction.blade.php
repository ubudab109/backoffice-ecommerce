{{-- KONFIRMASI PEMBAYARAN --}}
<div class="modal fade" id="modalStatusKonfirmasi" aria-labelledby="modalChangeStatusLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-custom">
      <div class="modal-content content-footer-status">
          <div class="modal-header" style="padding-top: 11px; padding-left: 36px; padding-bottom: 0; border: none;">
            <p class="title-notif" id="titleNotif" style="font-weight: bold">Konfirmasi Pembayaran</p>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
              <center>
                  <div class="row">
                      <div class="col-md-12" id="btnNotif">
                          <form action="{{route('post.status.transaction')}}" class="form-custom form stacked" method="POST"
                              id="formKonfirmasi" ajax="true">
                              <input type="hidden" name="idTransaction" id="idTransaction" value="{{$transaction->uuid}}">
                              <input type="hidden" name="statusChange" value="2">
                              <div class="col-12">
                                  <button class="btn btn-success btn-status" id="submitKonfirmasi" type="button">Konfirmasi</button>
                                  <button class="btn btn-danger btn-status cancel-transaction" type="button">Pesanan Dibatalkan</button>
                              </div>
                          </form>
                      </div>
                  </div>
              </center>
          </div>

      </div>
  </div>
</div>

{{-- PESANAN DIPROSES --}}
<div class="modal fade" id="modalToStatusProses" aria-labelledby="modalChangeStatusLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-custom">
      <div class="modal-content content-footer-status">
          <div class="modal-header" style="padding-top: 11px; padding-left: 36px; padding-bottom: 0; border: none;">
            <p class="title-notif" id="titleNotif" style="font-weight: bold">Proses Pesanan</p>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
              <center>
                  <div class="row">
                      <div class="col-md-12" id="btnNotif">
                        <div class="col-12">
                          <button data-toggle="modal" data-target="#modalStatusDikirim" class="btn btn-success btn-status" type="button">Kirim Pesanan</button>
                          <button class="btn btn-danger btn-status cancel-transaction" type="button">Pesanan Dibatalkan</button>
                      </div>
                      </div>
                  </div>
              </center>
          </div>

      </div>
  </div>
</div>


<div class="modal fade" id="modalStatusProses" aria-labelledby="modalChangeStatusLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-custom">
      <div class="modal-content content-footer-status">
          <div class="modal-header" style="padding-top: 11px; padding-left: 36px; padding-bottom: 0; border: none;">
            <p class="title-notif" id="titleNotif" style="font-weight: bold">Status Pesanan</p>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
              <center>
                  <div class="row">
                      <div class="col-md-12" id="btnNotif">
                          <form action="{{route('post.status.transaction')}}" class="form-custom form stacked" method="POST"
                              id="formPesananDiproses" ajax="true">
                              <input type="hidden" name="idTransaction" id="idTransaction" value="{{$transaction->uuid}}">
                              <input type="hidden" name="statusChange" value="3">
                              <div class="col-12">
                                  <button class="btn btn-success btn-status" id="submitProses" type="button">Konfirmasi</button>
                                  <button class="btn btn-danger btn-status cancel-transaction" type="button">Pesanan Dibatalkan</button>
                              </div>
                          </form>
                      </div>
                  </div>
              </center>
          </div>

      </div>
  </div>
</div>

{{-- PESANAN DIKIRIM --}}
<div class="modal fade" id="modalStatusDikirim" aria-labelledby="modalChangeStatusLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-custom">
      <div class="modal-content content-footer-status">
          <div class="modal-header" style="padding-top: 11px; padding-left: 36px; padding-bottom: 0; border: none;">
            <p class="title-notif" id="titleNotif" style="font-weight: bold">Pengaturan Kurir</p>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">

                  <div class="row">
                      <div class="col-md-12" id="btnNotif">
                          <form action="{{route('post.status.transaction')}}" class="form-custom form stacked" method="POST"
                              id="formPesananDikirim" ajax="true">
                              <input type="hidden" name="idTransaction" id="idTransaction" value="{{$transaction->uuid}}">
                              <input type="hidden" name="statusChange" value="4">

                              <div class="form-group row">
                                <label for="staticEmail" class="col-sm-5 col-form-label">Jenis Pengiriman</label>
                                <div class="col-sm-5">
                                  <select name="deliveredType" id="deliveredType" class="form-select select2">
                                    <option value="" selected>Pilih</option>
                                    <option value="kurir">Kurir AKOmart</option>
                                    <option value="ojol">Ojek Online</option>
                                    <option value="pickup">Pickup</option>
                                  </select>
                                </div>
                              </div>

                              <div class="form-group row">
                                <label for="staticEmail" class="col-sm-5 col-form-label">Nama Pengirim</label>
                                <div class="col-sm-5">
                                  <input id="postman_name" name="postmanName" type="text" class="form-control">
                                </div>
                              </div>


                              <div class="col-12">
                                  <button class="btn btn-success btn-status" id="kirimPesanan" type="button">Kirim Pesanan</button>
                              </div>
                          </form>
                      </div>
                  </div>
          </div>

      </div>
  </div>
</div>



{{-- PESANAN SELESAI --}}

<div class="modal fade" id="modalToStatusSelesai" aria-labelledby="modalChangeStatusLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-custom">
      <div class="modal-content content-footer-status">
          <div class="modal-header" style="padding-top: 11px; padding-left: 36px; padding-bottom: 0; border: none;">
            <p class="title-notif" id="titleNotif" style="font-weight: bold">Status Pesanan</p>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
              <center>
                  <div class="row">
                      <div class="col-md-12" id="btnNotif">
                        <div class="col-12">
                          <button data-toggle="modal" data-target="#modalStatusSelesai" class="btn btn-success btn-status" type="button">Pesanan Diterima</button>
                          <button class="btn btn-danger btn-status cancel-transaction" type="button">Pesanan Dibatalkan</button>
                      </div>
                      </div>
                  </div>
              </center>
          </div>

      </div>
  </div>
</div>
<div class="modal fade" id="modalStatusSelesai" aria-labelledby="modalChangeStatusLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-custom">
      <div class="modal-content content-footer-status">
          <div class="modal-header" style="padding-top: 11px; padding-left: 36px; padding-bottom: 0; border: none;">
            <p class="title-notif" id="titleNotif" style="font-weight: bold">Pengaturan Kurir</p>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">

                  <div class="row">
                      <div class="col-md-12" id="btnNotif">
                          <form action="{{route('post.status.transaction')}}" class="form-custom form stacked" method="POST"
                              id="formPesananSelesai" ajax="true">
                              <input type="hidden" name="idTransaction" id="idTransaction" value="{{$transaction->uuid}}">
                              <input type="hidden" name="statusChange" value="5">

                              <div class="form-group row">
                                <label for="staticEmail" class="col-sm-5 col-form-label">Nama Penerima</label>
                                <div class="col-sm-5">
                                  <input id="receiverName" name="receiverName" type="text" class="form-control">
                                </div>
                              </div>


                              <div class="col-12">
                                  <button class="btn btn-success btn-status" id="pesananSelesai" type="button">Pesanan Selesai</button>
                              </div>
                          </form>
                      </div>
                  </div>
          </div>

      </div>
  </div>
</div>






{{-- BATALKAN --}}
<div class="modal fade" id="modalStatusBatalkan" aria-labelledby="modalChangeStatusLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-custom">
      <div class="modal-content content-footer-status">
          <div class="modal-header" style="padding-top: 11px; padding-left: 36px; padding-bottom: 0; border: none;">
            <p class="title-notif" id="titleNotif" style="font-weight: bold">Anda Yakin Ingin Membatalkan Pesanan?</p>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
              <center>
                  <div class="row">
                      <div class="col-md-12" id="btnNotif">
                          <form action="{{route('post.status.transaction')}}" class="form-custom form stacked" method="POST"
                              id="formBatalkan" ajax="true">
                              <input type="hidden" name="idTransaction" id="idTransaction" value="{{$transaction->uuid}}">
                              <input type="hidden" name="statusChange" value="6">
                              <div class="col-12">
                                  <button class="btn btn-success btn-status submit" type="button">Yes</button>
                                  <button class="btn btn-danger btn-status" type="button" data-dismiss="modal" aria-label="Close">Cancel</button>
                              </div>
                          </form>
                      </div>
                  </div>
              </center>
          </div>

      </div>
  </div>
</div>