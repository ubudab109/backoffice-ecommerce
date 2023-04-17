@extends('main.main')
@section('content')
@include('main.topbar')

<div class="row mt-3">
  <div class="col-md-12">
    <div class="card card-custom">
      <div class="row">
        <div class="col-md-6">
          <p class="title-text pl-2">List Product</p>
        </div>
        @if (canAccess('product_management_add'))
        <div class="col-md-6 pr-4">
          <a href="{{route('add.product')}}"><img width="30" class="mt-4 right" src="{{asset('image/add.png')}}"></a>
        </div>
        @endif
      </div>
      <hr class="hr-custom">
      <div class="row">
        <div class="col-md-12 pl-4 pr-4 table-responsive">
          <table id="tableProduct" class="table-custom table table-strip stripe hover">
            <thead>
              <tr>
                <th class="text-center align-middle">Kode Produk</th>
                <th class="text-center align-middle">Nama Produk</th>
                <th class="text-center align-middle">Kategori Produk</th>
                <th class="text-center align-middle">Harga Produk</th>
                <th class="text-center align-middle">Status Promo</th>
                <th class="text-center align-middle">Harga Promo</th>
                <th class="text-center align-middle">Status Produk</th>
                <th class="text-center align-middle">Creator</th>
                <th class="text-center align-middle">Updated at</th>
                <th class="text-center align-middle">Action</th>
                <th class="text-center align-middle" style="visibility: hidden">Status</th>
                <th class="text-center align-middle" style="visibility: hidden">Promo Status</th>
                {{-- <th class="text-center align-middle">status</th>
                <th class="text-center align-middle">promo status</th> --}}
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
{{-- Status PRODUK --}}
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
              <form action="{{route('post.status.product')}}" class="form-custom form stacked" method="POST"
                id="formChangeStatusProduk" ajax="true">
                <input type="hidden" name="idProduk" id="idProdukStatus" value="">
                <input type="hidden" name="statusChange" id="statusChange">
                <div class="col-12">
                  <button class="btn btn-success btn-status" type="button" id="buttonStatusProduk">Yes</button>
                  <button class="btn btn-danger btn-status cancel_status" type="button" data-dismiss="modal" aria-label="Close" 
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

{{-- Status PROMO PRODUK --}}
<div class="modal fade" data-backdrop="static" id="modalChangeStatusPromo" aria-labelledby="modalChangeStatusPromoLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-custom">
    <div class="modal-content content-footer">
      <div class="modal-body">
        <center>
          <p class="title-notif" id="titleNotifPromo">Status promo dirubah menjadi “<span
              class="statusTextPromo"></span>” ?
          </p>
          <p class="content-notif" id="contentNotifPromo">Kamu akan merubah status promo dari <span
              id="fromPromo"></span> menjadi
            <br>
            <span class="statusText" id="toPromo"></span>, apakah kamu yakin ?
          </p>
        </center>
        <center>
          <div class="row">
            <div class="col-md-12" id="btnNotif">
              <form action="{{route('post.status-promo.product')}}" class="form-custom form stacked" method="POST"
                id="formChangeStatusPromoProduk" ajax="true">
                <input type="hidden" name="idProduk" id="idProdukStatusPromo" value="">
                <input type="hidden" name="statusPromoChange" id="statusChangePromo">
                <input type="hidden" name="priceProduct" id="priceValueProduct">
                {{-- FOR ACTIVE --}}
                <div class="row d-none" id="formPromo">
                  <div class="col-md-12 mt-2">
                    <div class="form-group">
                      <label for="">Harga Produk</label>
                      <div class="col-md-12 p-0">
                        <input type="text" disabled id="currentProductPrice" class="form-control">
                      </div>
                    </div>
                  </div>
  
                  <div class="col-md-12 mt-2" id="formPromoType">
                    <div class="form-group">
                      <label for="">Tipe Promo</label>
                      <div class="col-md-12 p-0">
                        <select name="promoType" id="promoType" class="form-select select2">
                          <option value="">Tipe Promo</option>
                          <option value="fixed">Harga</option>
                          <option value="discount">Diskon</option>
                        </select>
                      </div>
                    </div>
                  </div>
  
                  <div class="col-md-12 mt-2" id="formPromoValue">
                    <div class="form-group">
                      <label for="">Potongan Promo</label>
                      <div class="col-md-12 p-0">
                        <div class="with-error">
                          <input type="hidden" name="promoValue" id="promoValue" class="form-control">
                          <input disabled type="text" name="promoValueInput" id="promoValueInput" class="form-control">
                        </div>
                      </div>
                    </div>
                  </div>
  
                  <div class="col-md-12 mt-2" id="formTotalPrice">
                    <div class="form-group">
                      <label for="">Total Harga</label>
                      <div class="with-error">
                        <div class="col-md-12 p-0">
                          <input readonly type="text" name="priceProduct" id="priceProduct" class="form-control">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <hr class="hr-card">
  
                {{-- END --}}

                <div class="col-12">
                  <button class="btn btn-success btn-status" type="button" id="buttonStatusPromoProduk">Yes</button>
                  <button class="btn btn-danger btn-status cancel_status_promo" type="button" data-dismiss="modal" aria-label="Close" 
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
<div class="modal fade" id="modalDeleteProduk" aria-labelledby="modalDeleteLabel" aria-hidden="true">
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
              <form action="{{route('post.delete.product')}}" class="form-custom form stacked" method="POST"
                id="formDeleteProduk" ajax="true">
                <input type="hidden" name="idProduk" id="idProdukDelete" value="">
                <div class="col-12">
                  <button class="btn btn-success btn-status" type="button" id="deleteButtonProduk">Yes</button>
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
  function formatPercent(angka) {
    var number_string = angka.replace(/[^,\d]/g, "").toString(),
      split = number_string.split(","),
      sisa = split[0].length % 3,
      percent = split[0].substr(0, sisa),
      ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    // tambahkan titik jika yang di input sudah menjadi angka ribuan
    if (ribuan) {
      separator = sisa ? "." : "";
      percent += separator + ribuan.join(".");
    }

    percent = split[1] != undefined ? percent + split[1] : percent;
    // return prefix == undefined ? percent : percent ? "Rp. " + percent : "";
    return percent + '%'
  }
  
  function openModalDelete(idProduct) {
    $("#idProdukDelete").val(idProduct);
    $("#modalDeleteProduk").modal('show');
  }

  function openModalStatus(status, idProduct) {
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
    $("#idProdukStatus").val(idProduct);
    $("#modalChangeStatus").modal('show');
  }

  function openModalStatusPromo(status, idProduct, promoPrice, promoType, promoValue, price) {
    if (status == '') {
      $("#promoValueInput").attr('disabled', true);
      $("#promoValue").val('');
      $("#promoValueInput").val(formatRupiahRp(''));
    }
    if (status == '1') {
      // $("#promoValueInput").removeAttr('disabled');
      $("#formPromo").addClass('d-none');
      $("#fromPromo").text('Active');
      $("#toPromo").text('Inactive');
      $("#statusChangePromo").val('0');
    } else {
      // $("#promoValueInput").removeAttr('disabled');
      $("#currentProductPrice").val(formatRupiahRp(price.toString()));
      $("#priceValueProduct").val(price);
      $('#promoType').val(promoType)
      $("#promoValue").val(promoValue);
      if (promoType == 'fixed') {
        $("#promoValueInput").val(formatRupiahRp(promoValue.toString()));
      } else if (promoType == 'discount') {
        $("#promoValueInput").val(formatPercent(promoValue.toString()));
      } else {
        $("#promoValueInput").val(formatRupiahRp(promoValue.toString()));
      }
      $("#priceProduct").val(formatRupiahRp(promoPrice.toString()))
      $("#formPromo").removeClass('d-none');
      $("#fromPromo").text('Inactive');
      $("#toPromo").text('Active');
      $("#statusChangePromo").val('1');
    }
    $('.statusTextPromo').text(`${status == '1' ? 'Inactive' : 'Active'}`)
    $("#idProdukStatusPromo").val(idProduct);
    $("#modalChangeStatusPromo").modal('show');
  }
</script>
@endsection