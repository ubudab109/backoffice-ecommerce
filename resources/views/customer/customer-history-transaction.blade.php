@extends('main.main')
@section('content')
@include('main.topbar')

<div class="row mt-3">
  <div class="col-md-12">
    <div class="card card-custom">
      <div class="row">
        <div class="col-md-12">
          <p class="title-text pl-2">List Transaksi</p>
          <hr class="hr-card">
        </div>
        <div class="col-md-12 pl-4 pr-4 table-responsive">
          
          <table id="tableCustomerTransaksi" class="table-custom table table-strip stripe hover">
            <thead>
              <tr>
                <th class="text-center align-middle">ID Transaksi</th>
                <th class="text-center align-middle">No Invoice</th>
                <th class="text-center align-middle">Nama Customer</th>
                <th class="text-center align-middle">Whatsapp Customer</th>
                <th class="text-center align-middle">Tanggal Transaksi</th>
                <th class="text-center align-middle">Total</th>
                <th class="text-center align-middle">Status Pesanan</th>
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
</div>
@endsection