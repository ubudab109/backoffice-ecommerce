@extends('main.main')
@section('content')
@include('main.topbar')

<div class="row mt-3">
  <div class="col-md-12">
    <div class="card card-custom">
      <div class="row">
        <div class="col-md-6">
          <p class="title-text pl-2">List Inventory</p>
        </div>
        @if (canAccess('inventory_management_add'))
        <div class="col-md-6 pr-4">
          <a href="{{route('add.inventory')}}"><img width="30" class="mt-4 right" src="{{asset('image/add.png')}}"></a>
        </div>
        @endif
      </div>
      <hr class="hr-custom">
      <div class="row">
        <div class="col-md-12 pl-4 pr-4 table-responsive">
          <table id="tableInventory" class="table-custom table table-strip stripe hover">
            <thead>
              <tr>
                <th class="text-center align-middle">Kode Produk</th>
                <th class="text-center align-middle">Nama Produk</th>
                <th class="text-center align-middle">Kategori Produk</th>
                <th class="text-center align-middle">Harga Produk</th>
                <th class="text-center align-middle">Status Promo</th>
                <th class="text-center align-middle">Harga Promo</th>
                <th class="text-center align-middle">Status Produk</th>
                <th class="text-center align-middle">Total Inventory</th>
                <th class="text-center align-middle">Total Terjual</th>
                <th class="text-center align-middle">Total Saat Ini</th>
                <th class="text-center align-middle">Action</th>
                <th class="text-center align-middle" style="visibility: hidden">Status</th>
                <th class="text-center align-middle" style="visibility: hidden">Promo Status</th>
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
{{-- DELETE --}}
<div class="modal fade" id="modalDeleteInventory" aria-labelledby="modalDeleteLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-custom">
    <div class="modal-content content-footer-status">
      <div class="modal-body">
        <center>
          <img src="{{asset('/image/delete_modal.svg')}}" alt="">
          <p class="title-notif" id="titleNotif">Anda Yakin Ingin Menghapus ?</p>
        </center>
        <center>
          <div class="row">
            <div class="col-md-12" id="btnNotif">
              <form action="{{route('post.delete.inventory')}}" class="form-custom form stacked" method="POST"
                id="formDeleteInventory" ajax="true">
                <input type="hidden" name="idInventory" id="idInventoryDelete" value="">
                <div class="col-12">
                  <button class="btn btn-success btn-status" type="button" id="deleteButtonInventory">Lanjutkan</button>
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
  function openModalDelete(idInventory) {
            $("#idInventoryDelete").val(idInventory);
            $("#modalDeleteInventory").modal('show');
        }
</script>
@endsection