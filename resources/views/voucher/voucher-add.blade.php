@extends('main.main')
@section('content')
@include('main.topbar')

<div class="row mt-3">
  <div class="col-md-12">
    <div class="card card-custom">
      <div class="row">
        <div class="col-md-6">
          <p class="title-text pl-2">Add Voucher</p>
        </div>
      </div>
      <hr class="hr-custom">
      <div class="row">
        <div class="col-md-12">
          <form action="{{route('post.add.voucher')}}" class="form-custom form stacked" method="POST"
            id="formAddVoucher" ajax="true">

            <div class="form-group row">
              <label for="staticEmail" class="col-sm-2 col-form-label">Judul Voucher*</label>
              <div class="col-sm-5">
                <input type="text" name="judulVoucher" id="judulVoucher" class="form-control"
                  placeholder="Masukkan Judul Voucher">
              </div>
            </div>

            <div class="form-group row">
              <label for="staticEmail" class="col-sm-2 col-form-label">Deskripsi*</label>
              <div class="col-sm-5">
                <input type="text" name="description" id="description" class="form-control"
                  placeholder="Masukan Deskripsi Voucher">
              </div>
            </div>

            <div class="form-group row">
              <label for="staticEmail" class="col-sm-2 col-form-label">Min Total Belanja*</label>
              <div class="col-sm-5">
                <input type="hidden" name="minimumShoping" id="minimumShoping" class="form-control">
                <input type="text" name="minimumShopingInput" id="minimumShopingInput" class="form-control"
                  placeholder="Masukkan Minimal Total Belanja">
              </div>
            </div>


            <div class="form-group row">
              <label for="staticEmail" class="col-sm-2 col-form-label">Jenis Diskon*</label>
              <div class="col-sm-5">
                <select name="promoType" id="promoType" class="form-select select2">
                  <option value="" selected>Pilih</option>
                  <option value="fixed">Harga</option>
                  <option value="discount">Diskon</option>
                </select>
              </div>
            </div>

            <div class="form-group row">
              <label for="staticEmail" class="col-sm-2 col-form-label">Jumlah Diskon*</label>
              <div class="col-sm-5">
                <input type="hidden" name="promoValue" id="promoValue" class="form-control">
                <input readonly type="text" placeholder="Masukkan Jumlah Diskon" name="promoValueInput"
                  id="promoValueInput" class="form-control">
              </div>
            </div>

            <div class="form-group row">
              <label for="staticEmail" class="col-sm-2 col-form-label">Maximal Diskon*</label>
              <div class="col-sm-5">
                <input readonly type="text" name="maximalDiskonInput" id="maximalDiskonInput" class="form-control">
                <input type="hidden" name="maximalDiskon" id="maximalDiskon" class="form-control">
              </div>
            </div>


            <div class="form-group row">
              <label for="staticEmail" class="col-sm-2 col-form-label">Kode Voucher*</label>
              <div class="col-sm-5">
                <input placeholder="Masukkan Kode Voucher" type="text" name="codeVoucher" id="codeVoucher"
                  class="form-control">
              </div>
            </div>

            <div class="form-group row">
              <label for="staticEmail" class="col-sm-2 col-form-label">Kuota*</label>
              <div class="col-sm-5">
                <input placeholder="Masukkan Kuota Voucher" type="number" name="kuota" id="kuota" class="form-control">
              </div>
            </div>

            <div class="form-group row">
              <label for="staticEmail" class="col-sm-2 col-form-label">Mulai Berlaku*</label>
              <div class="col-sm-5">
                <input type="date" name="startVoucher" id="startVoucher" class="form-control">
              </div>
            </div>

            <div class="form-group row">
              <label for="staticEmail" class="col-sm-2 col-form-label">Selesai Berlaku*</label>
              <div class="col-sm-5">
                <input type="date" name="endVoucher" id="endVoucher" class="form-control">
              </div>
            </div>

            <div class="form-group row">
              <label for="staticEmail" class="col-sm-2 col-form-label">Tipe Voucher*</label>
              <div class="col-sm-5">
                <select name="typeVoucher" id="typeVoucher" class="form-select select2">
                  <option value="" selected>Tipe Voucher</option>
                  <option value="price_deducted">Potongan Harga</option>
                </select>
              </div>
            </div>

            <div class="form-group row">
              <label for="staticEmail" class="col-sm-2 col-form-label">Status</label>
              <div class="custom-control custom-switch ml-3">
                <input type="checkbox" name="voucherStatus" id="voucherStatus" checked class="custom-control-input">
                <label class="custom-control-label" for="voucherStatus"></label>
              </div>
            </div>

            <div class="row mb-3">
              <button type="button" id="btnResetAddVoucher" class="btn btn-reset ml-2 mr-4">Reset</button>
              <button type="button" id="btnAddVoucher" class="btn btn-save">Simpan</button>
            </div>
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
<div class="modal fade" id="modalAddVoucher" aria-labelledby="modalAddVoucherLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-custom">
    <div class="modal-content content-footer-status">
      <div class="modal-body">
        <center>
          <p class="title-notif" id="titleNotif">Kamu akan menambahkan Voucher ?</p>
        </center>
        <center>
          <div class="row">
            <div class="col-md-12" id="btnNotif">
              <form action="{{route('post.add.voucher')}}" class="form-custom form stacked" method="POST"
                id="formAddVoucher" ajax="true">
                <button class="btn btn-success btn-status" type="submit" id="submitVoucher"
                  class="btn-notif">Yes</button>
                <button class="btn btn-danger btn-status cancel-status" type="button" data-dismiss="modal"
                  aria-label="Close" class="btn-notif-cancel" id="">Cancel</button>
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
  function formatRupiahRp(angka) {
      var number_string = angka.replace(/[^,\d]/g, "").toString(),
        split = number_string.split(","),
        sisa = split[0].length % 3,
        rupiah = split[0].substr(0, sisa),
        ribuan = split[0].substr(sisa).match(/\d{3}/gi);

      // tambahkan titik jika yang di input sudah menjadi angka ribuan
      if (ribuan) {
        separator = sisa ? "." : "";
        rupiah += separator + ribuan.join(".");
      }

      rupiah = split[1] != undefined ? rupiah + split[1] : rupiah;
      // return prefix == undefined ? rupiah : rupiah ? "Rp. " + rupiah : "";
      return 'Rp. ' + rupiah
  }
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

  $("#minimumShopingInput").keyup(() => {
    var input = $("#minimumShopingInput");
    $("#minimumShopingInput").val(formatRupiahRp(input.val().toString()));
    var splitedRp = input.val().split('Rp.');
    var splittedPrice = splitedRp[1].split('.');
    var test = document.getElementById('minimumShoping').value = splittedPrice.join('');
  });

  $("#promoValueInput").keyup(() => {
    var type = $("#promoType").val();
    var value = $("#promoValueInput");
    var maximal = $("#maximalDiskonInput");
    var maximalValue = $("#maximalDiskon")
    if (type == 'discount') {
      value.val(formatPercent(value.val().toString()));
      var splitedDiscount = value.val().split('%');
			var splittedPrice = splitedDiscount.join('');
			var splitValueDiscount = splittedPrice.split('.');
			var realDiscount = splitValueDiscount.join('');
      document.getElementById('promoValue').value = realDiscount;
    } else {
      value.val(formatRupiahRp(value.val().toString()));
      var splitedRp = value.val().split('Rp.');
      var splittedPrice = splitedRp[1].split('.');
      maximal.val(formatRupiahRp(value.val().toString()));
      maximalValue.val(splittedPrice.join(''));
      var test = document.getElementById('promoValue').value = splittedPrice.join('');
    }
  });

  $("#promoType").change(() => {
    var type = $("#promoType option:selected").val();
    if (type == 'fixed') {
      $("#maximalDiskonInput").attr('readonly', true);
      $("#promoValueInput").attr('readonly', false);
    } else if (type == 'discount') {
      $("#maximalDiskonInput").attr('readonly', false);
      $("#promoValueInput").attr('readonly', false);
    } else {
      $("#promoValueInput").attr('readonly', true);
      $("#maximalDiskonInput").attr('readonly', true);
    }
  });

  $("#maximalDiskonInput").keyup(() => {
    var type = $("#promoType").val();
    var maximal = $("#maximalDiskonInput");
    var maximalInput = $("#maximalDiskon");
    if (type == 'discount') {
      maximal.val(formatRupiahRp(maximal.val().toString()));
      var splitedRp = maximal.val().split('Rp.');
      var splittedPrice = splitedRp[1].split('.');
      var test = document.getElementById('maximalDiskon').value = splittedPrice.join('');
    }
  });
  
</script>
@endsection