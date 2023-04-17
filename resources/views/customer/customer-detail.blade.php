@extends('main.main')
@section('content')
@include('main.topbar')

<div class="row mt-3">
  <div class="col-md-12">
    <div class="card card-custom">
      <div class="row">
        <div class="col-md-6">
          <p class="title-text pl-2">Detail Customer</p>
        </div>
      </div>
      <hr class="hr-custom">

      <div class="row">
        <div class="col-md-12">

            <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">ID Customer</label>
                <div class="col-sm-10">
                  <input type="text" readonly class="form-control-plaintext" value=": {{$customer->id}}">
                </div>
            </div>

            <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Nama</label>
                <div class="col-sm-10">
                  <input type="text" readonly class="form-control-plaintext" value=": {{$customer->name}}">
                </div>
            </div>

            <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">No Whatsapp</label>
                <div class="col-sm-10">
                  <input type="text" readonly class="form-control-plaintext" value=": {{$customer->whatsapp}}">
                </div>
            </div>

            <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Alamat</label>
                <div class="col-sm-10">
                  <?php $customerAddress = $customer->address()->where('is_default',true)->first(); ?>
                  <input type="text" readonly class="form-control-plaintext" value=": {{$customerAddress ? $customerAddress->address : 'Belum Ditambahkan'}}">
                  <a href="#" data-toggle="modal" class="ml-2" style="color: #35BA4D; font-size: 14px;" data-target="#modalAddressCustomer">View More</a>
                </div>
            </div>

            <div class="col-md-12">
              <button class="btn btn-save mb-5" style="width: 185px;"><a href="/customer/detail-customer/transaction?customer={{$customer->id}}" style="font-size: 18px; color: #FFFFFF;">History Transaksi</a></button>
            </div>
        </div>
      </div>

    </div>
  </div>
</div>
</div>

@endsection

@section('modal')
<div class="modal fade" id="modalAddressCustomer" aria-labelledby="modalAddressCustomerLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-custom">
        <div class="modal-content content-footer-status">
            <div class="modal-body">
                @php
                  $alamatUtama = $customer->address()->where('is_default',true)->first();
                  $alamatLain = $customer->address()->where('is_default',false)->get();
                @endphp
                <h5 class="text-bold">Alamat Utama</h5>
                <div class="card mb-5">
                  <div class="card-body">
                    <h5 class="card-title">{{$alamatUtama? $alamatUtama->title : 'Belum Diatur'}}</h5>
                    <p class="card-text">{{$alamatUtama? $alamatUtama->address : 'Belum Diatur'}}</p>
                  </div>
                </div>

                <h5 class="text-bold">Alamat Lainnya</h5>
                @foreach ($alamatLain as $alamat)
                <div class="card mb-2">
                  <div class="card-body">
                    <h5 class="card-title">{{$alamat->title}}</h5>
                    <p class="card-text">{{$alamat->address}}</p>
                  </div>
                </div>
                @endforeach
            </div>

        </div>
    </div>
</div>
@endsection

@section('script')
<script>
  $('.summernote').summernote('disable');
</script>
@endsection