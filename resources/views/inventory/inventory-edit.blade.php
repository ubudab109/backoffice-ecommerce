@extends('main.main')
@section('content')
@include('main.topbar')

<div class="row mt-3">
  <div class="col-md-12">
    <div class="card card-custom">
      <div class="row">
        <div class="col-md-6">
          <p class="title-text pl-2">Edit Inventory</p>
        </div>
      </div>
      <hr class="hr-custom">
      <div class="row">
        <div class="col-md-12">
          <form enctype="multipart/form-data" action="{{route('post.edit.inventory')}}" class="form-custom form stacked"
            method="POST" id="formEditInventory" ajax="true">
            <input type="hidden" value="{{$inventory->id}}" name="idInventory">


            <div class="form-group row">
              <label for="staticEmail" class="col-sm-2 col-form-label">Kode Product</label>
              <div class="col-sm-5">
                <input type="text" readonly disabled id="codeProduct" class="form-control-plaintext"
                      value=": {{$inventory->product->code_product}}" placeholder="Masukkan Kode Kategori">
              </div>
            </div>

            <div class="form-group row">
              <label for="staticEmail" class="col-sm-2 col-form-label">Nama Product</label>
              <div class="col-sm-5">
                <input type="text" readonly disabled value=": {{$inventory->product->name}}"
                      class="form-control-plaintext">
              </div>
            </div>

            <div class="form-group row">
              <label for="staticEmail" class="col-sm-2 col-form-label">Nama Produk</label>
              <div class="col-sm-5">
                <input type="text" readonly disabled value=": {{$inventory->product->name}}"
                      class="form-control-plaintext">
              </div>
            </div>

            <div class="form-group row">
              <label for="staticEmail" class="col-sm-2 col-form-label">Kategori Produk</label>
              <div class="col-sm-5">
                <input type="text" readonly disabled value=": {{$inventory->product->category->name}}"
                      class="form-control-plaintext">
              </div>
            </div>

            <div class="form-group row">
              <label for="staticEmail" class="col-sm-2 col-form-label">Harga Produk</label>
              <div class="col-sm-5">
                <input type="text" readonly disabled value=": {{rupiah($inventory->product->price)}}"
                      class="form-control-plaintext">
              </div>
            </div>

            <div class="form-group row">
              <label for="staticEmail" class="col-sm-2 col-form-label">Total Inventory</label>
              <div class="col-sm-5">
                <input {{canAccess('inventory_management_edit') ? '' : 'disabled' }} type="text"
                      value="{{$inventory->qty}}" id="totalInventory" name="totalInventory" class="form-control">
              </div>
            </div>

            <div class="form-group row">
              <label for="staticEmail" class="col-sm-2 col-form-label">Produk Terjual</label>
              <div class="col-sm-5">
                <input readonly type="text" value=": {{$inventory->total_sold}}" class="form-control-plaintext">
              </div>
            </div>

            <div class="row mb-3">
              <button type="button" id="btnResetEditInventory" class="btn btn-reset ml-2 mr-4">Reset</button>
              @if(canAccess('inventory_management_edit'))
              <button type="button" id="btnEditInventory" class="btn btn-save">Simpan</button>
              @endif
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('modal')
<div class="modal fade" id="modalEditInventory" aria-labelledby="modalEditInventoryLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-custom">
    <div class="modal-content content-footer-status">
      <div class="modal-body">
        <center>
          <p class="title-notif" id="titleNotif">Kamu akan merubah data tersebut dan data akan <br>
            tergantikan dengan yang baru, apakah kamu yakin ?</p>
        </center>
        <center>
          <div class="row">
            <div class="col-md-12" id="btnNotif">
              <form enctype="multipart/form-data" action="{{route('post.edit.inventory')}}"
                class="form-custom form stacked" method="POST" id="formEditInventory" ajax="true">

                <button class="btn btn-success btn-status" type="submit" class="btn-notif">Yes</button>
                <button class="btn btn-danger btn-status cancel-status" type="button" data-dismiss="modal" aria-label="Close" class="btn-notif-cancel"
                    id="">Cancel</button>
              </form>
            </div>
          </div>
        </center>
      </div>

    </div>
  </div>
</div>
@endsection