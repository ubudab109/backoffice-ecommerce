@extends('main.main')
@section('content')
@include('main.topbar')

<div class="row mt-3">
  <div class="col-md-12">
    <div class="card card-custom">
      <div class="row">
        <div class="col-md-6">
          <p class="title-text pl-2">Detail Voucher</p>
        </div>
      </div>
      <hr class="hr-custom">
      <div class="row">
        <div class="col-md-12">
          <div class="form-group row">
            <label for="staticEmail" class="col-sm-2 col-form-label">Judul Voucher</label>
            <div class="col-sm-5">
              <input type="text" readonly class="form-control-plaintext" value=": {{$voucher->title}}" name="judulVoucher" id="judulVoucher"  placeholder="Masukkan Judul Voucher">
            </div>
          </div>

          <div class="form-group row">
            <label for="staticEmail" class="col-sm-2 col-form-label">Deskripsi</label>
            <div class="col-sm-5">
              <input type="text" readonly class="form-control-plaintext" value=": {{$voucher->description}}" name="description" id="description" placeholder="Masukan Deskripsi Voucher">
            </div>
          </div>

          <div class="form-group row">
            <label for="staticEmail" class="col-sm-2 col-form-label">Min Total Belanja</label>
            <div class="col-sm-5">
              <input type="hidden" name="minimumShoping" id="minimumShoping" class="form-control">
              <input type="text" readonly class="form-control-plaintext" value=": {{rupiah($voucher->minimum_shop_price)}}"  name="minimumShopingInput" id="minimumShopingInput" placeholder="Masukkan Minimal Total Belanja">
            </div>
          </div>


          <div class="form-group row">
            <label for="staticEmail" class="col-sm-2 col-form-label">Jenis Diskon</label>
            <div class="col-sm-5">
              <input type="text" readonly class="form-control-plaintext" value=": {{ucwords($voucher->discount_type)}}" name="minimumShopingInput" id="promoType" placeholder="Masukkan Minimal Total Belanja">
            </div>
          </div>

          <div class="form-group row">
            <label for="staticEmail" class="col-sm-2 col-form-label">Jumlah Diskon</label>
            <div class="col-sm-5">
              <input type="hidden" name="promoValue" id="promoValue" class="form-control">
              @if ($voucher->discount_type == 'fixed')
              <input readonly class="form-control-plaintext" value=": {{rupiah($voucher->discount_value)}}" type="text" placeholder="Masukkan Jumlah Diskon" name="promoValueInput"
                id="promoValueInput">
              @elseif ($voucher->discount_type == 'discount')
              <input readonly class="form-control-plaintext" value=": {{$voucher->discount_value}}%" type="text" placeholder="Masukkan Jumlah Diskon" name="promoValueInput"
                id="promoValueInput">
              @endif
            </div>
          </div>

          <div class="form-group row">
            <label for="staticEmail" class="col-sm-2 col-form-label">Maximal Diskon</label>
            <div class="col-sm-5">
              <input readonly class="form-control-plaintext" value=": {{rupiah($voucher->discount_maximal)}}" type="text" placeholder="Masukkan Jumlah Diskon" name="promoValueInput"
                id="promoValueInput" class="form-control">
            </div>
          </div>


          <div class="form-group row">
            <label for="staticEmail" class="col-sm-2 col-form-label">Kode Voucher</label>
            <div class="col-sm-5">
              <input placeholder="Masukkan Kode Voucher" type="text" name="codeVoucher" id="codeVoucher"
              readonly class="form-control-plaintext" value=": {{$voucher->code}}">
            </div>
          </div>

          <div class="form-group row">
            <label for="staticEmail" class="col-sm-2 col-form-label">Kuota</label>
            <div class="col-sm-5">
              <input type="text"  class="form-control-plaintext" value=": {{$voucher->kuota}}">
            </div>
          </div>

          <div class="form-group row">
            <label for="staticEmail" class="col-sm-2 col-form-label">Sisa Voucher</label>
            <div class="col-sm-5">
              <input type="text"  class="form-control-plaintext" value=": {{$voucher->rest_voucher}}">
            </div>
          </div>

          <div class="form-group row">
            <label for="staticEmail" class="col-sm-2 col-form-label">Voucher Terpakai</label>
            <div class="col-sm-5">
              <input type="text"  class="form-control-plaintext" value=": {{$voucher->total_reedem}}">
            </div>
          </div>

          <div class="form-group row">
            <label for="staticEmail" class="col-sm-2 col-form-label">Mulai Berlaku</label>
            <div class="col-sm-5">
              <input type="text"  class="form-control-plaintext" value=": {{$voucher->date_start_voucher}}">
            </div>
          </div>

          <div class="form-group row">
            <label for="staticEmail" class="col-sm-2 col-form-label">Selesai Berlaku</label>
            <div class="col-sm-5">
              <input type="text" name="endVoucher" id="endVoucher" class="form-control-plaintext" value=": {{$voucher->date_end_voucher}}">
            </div>
          </div>

          <div class="form-group row">
            <label for="staticEmail" class="col-sm-2 col-form-label">Tipe Voucher</label>
            <div class="col-sm-5">
              <input type="text" name="typeVoucher" id="typeVoucher" class="form-control-plaintext" value=": Potongan Harga">
            </div>
          </div>

          <div class="form-group row">
            <label for="staticEmail" class="col-sm-2 col-form-label">Status</label>
            <div class="col-sm-10">
              <input type="text" readonly class="form-control-plaintext" value=": {{$voucher->status ? 'Aktif' : 'Tidak Aktif'}}">
            </div>
          </div>
          @if (canAccess('voucher_management_edit'))
            <div class="row mb-3 mt-3">
              <button type="button" class="btn btn-reset ml-2 mr-4">
                <a href="{{route('get.edit-detail.voucher', $voucher->id)}}" style="color: white">Edit</a>
              </button>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
</div>
</div>

@endsection