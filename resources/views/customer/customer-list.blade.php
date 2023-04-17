@extends('main.main')
@section('content')
@include('main.topbar')

<div class="row mt-3">
  <div class="col-md-12">
    <div class="card card-custom">
      <div class="row">
        <div class="col-md-6">
          <p class="title-text pl-2">List Customer</p>
        </div>
      </div>
      <hr class="hr-custom">
      <div class="row">
        <div class="col-md-12 pl-4 pr-4 table-responsive">
          <table id="tableCustomer" class="table-custom table table-strip stripe hover">
            <thead>
              <tr>
                <th class="text-center align-middle">ID</th>
                <th class="text-center align-middle">No Whatsapp Customer</th>
                <th class="text-center align-middle">Nama Customer</th>
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