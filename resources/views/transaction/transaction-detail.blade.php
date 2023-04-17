@extends('main.main')
@section('content')
@include('main.topbar')

<div class="row mt-3">
  <div class="col-md-12">
    <div class="card card-custom">
      <div class="row">
        <div class="col-md-6">
          <p class="title-text pl-2">Detail Transaksi</p>
        </div>
        <div class="col-6 text-right mt-3">
          <a class="btn btn-primary" href="{{route('get.download.invoice', ['id' => $transaction->id])}}">Download Struk</a>
        </div>
      </div>
      <hr class="hr-custom">

      <div class="row">
        <div class="col-md-12">
          <form class="form-custom form stacked" id="formEditProduct" ajax="true" files="true" multipleFiles="true">
            <div class="row">
              <div class="col-xl-9 col-lg-12 col-md-12 col-sm-12">
                <div class="form-group row">
                    <label for="staticEmail" class="col-xl-3 col-lg-4 col-md-8 col-sm-12 col-form-label text-muted">ID Transaksi</label>
                    <div class="col-sm-9">
                      <input type="text" readonly class="form-control-plaintext" value=": {{$transaction->id}}">
                    </div>
                </div>
  
                <div class="form-group row">
                    <label for="staticEmail" class="col-xl-3 col-lg-4 col-md-8 col-sm-12 col-form-label text-muted">Nomor Invoice</label>
                    <div class="col-sm-9">
                      <input type="text" readonly class="form-control-plaintext" value=": {{$transaction->no_invoice}}">
                    </div>
                </div>
  
                <div class="form-group row">
                    <label for="staticEmail" class="col-xl-3 col-lg-4 col-md-8 col-sm-12 col-form-label text-muted">Tanggal Transaksi</label>
                    <div class="col-sm-9">
                      <input type="text" readonly class="form-control-plaintext" value=": {{date('d F Y', strtotime($transaction->transaction_date))}}">
                    </div>
                </div>
  
                <div class="form-group row">
                    <label for="staticEmail" class="col-xl-3 col-lg-4 col-md-8 col-sm-12 col-form-label text-muted">Jam Transaksi</label>
                    <div class="col-sm-9">
                      <input type="text" readonly class="form-control-plaintext" value=": {{date('h:i A', strtotime($transaction->transaction_date))}}">
                    </div>
                </div>
  
                {{-- DETAIL CUSTOMER --}}
                <div class="form-group row">
                  <label for="staticEmail" class="col-xl-3 col-lg-4 col-md-8 col-sm-12 col-form-label">Detail Customer</label>
                </div>
  
                <div class="form-group row">
                    <label for="staticEmail" class="col-xl-3 col-lg-4 col-md-8 col-sm-12 col-form-label text-muted">Nama Customer</label>
                    <div class="col-sm-9">
                      <input type="text" readonly class="form-control-plaintext" value=": {{$transaction->customer->name}}">
                    </div>
                </div>
  
                <div class="form-group row">
                    <label for="staticEmail" class="col-xl-3 col-lg-4 col-md-8 col-sm-12 col-form-label text-muted">Whatsapp Customer</label>
                    <div class="col-sm-9">
                      <input type="text" readonly class="form-control-plaintext" value=": {{$transaction->customer->whatsapp}}">
                    </div>
                </div>
  
                @if ($transaction->receiver()->first() != null)    
                {{-- DETAIL PENERIMA --}}
                <div class="form-group row">
                  <label for="staticEmail" class="col-xl-3 col-lg-4 col-md-8 col-sm-12 col-form-label">Detail Penerima</label>
                </div>
  
                <div class="form-group row">
                    <label for="staticEmail" class="col-xl-3 col-lg-4 col-md-8 col-sm-12 col-form-label text-muted">Nama Penerima</label>
                    <div class="col-sm-9">
                      <input type="text" readonly class="form-control-plaintext" value=": {{$transaction->receiver()->first()->receiver_name}}">
                    </div>
                </div>
  
                <div class="form-group row">
                    <label for="staticEmail" class="col-xl-3 col-lg-4 col-md-8 col-sm-12 col-form-label text-muted">Nomor Whatsapp Penerima</label>
                    <div class="col-sm-9">
  
                      @if ($transaction->receiver()->first()->receiver_whatsapp != null)
                      <input type="text" readonly class="form-control-plaintext" value=": {{$transaction->receiver()->first()->receiver_whatsapp}}">
                      @else
                      <input type="text" readonly class="form-control-plaintext" value=": {{$transaction->customer->whatsapp}}">
                      @endif
                    </div>
                </div>
                @endif
                {{-- Detail Pengiriman --}}
                <div class="form-group row">
                  <label for="staticEmail" class="col-xl-3 col-lg-4 col-md-8 col-sm-12 col-form-label">Detail Pengiriman</label>
                </div>
                <div class="form-group row">
                    <label for="staticEmail" class="col-xl-3 col-lg-4 col-md-8 col-sm-12 col-form-label text-muted">Jenis Pengiriman</label>
                    <div class="col-sm-9">
                        @if ($transaction->delivered_type == 'ojol' || $transaction->delivered_type == 'kurir') 
                          <input readonly disabled type="text" value=": Delivered"
                            class="form-control-plaintext">
                        @else
                          <input readonly disabled type="text" value=": Pick Up"
                          class="form-control-plaintext">
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label for="staticEmail" class="col-xl-3 col-lg-4 col-md-8 col-sm-12 col-form-label text-muted">Kota Pengiriman</label>
                    <div class="col-sm-9">
                      <input type="text" readonly class="form-control-plaintext" value=": {{ucwords($transaction->city)}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="staticEmail" class="col-xl-3 col-lg-4 col-md-8 col-sm-12 col-form-label text-muted">Alamat Pengiriman</label>
                    <div class="col-sm-9">
                      <input type="text" readonly name="" id="" class="form-control-plaintext" value=": {{$transaction->shipping_address}}">
                    </div>
                </div>
                @if ($transaction->transaction_send_date != null)
                  <div class="form-group row">
                      <label for="staticEmail" class="col-xl-3 col-lg-4 col-md-8 col-sm-12 col-form-label text-muted">Tanggal Pengiriman</label>
                      <div class="col-sm-9">
                        <input type="text" disabled value=": {{date('d F Y', strtotime($transaction->transaction_send_date))}}" class="form-control-plaintext">
                      </div>
                  </div>
                  <div class="form-group row">
                      <label for="staticEmail" class="col-xl-3 col-lg-4 col-md-8 col-sm-12 col-form-label text-muted">Jam Pengiriman</label>
                      <div class="col-sm-9">
                        <input type="text" disabled value=": {{$transaction->getJamPengirimanAttribute()}}" class="form-control-plaintext">
                      </div>
                  </div>
                @endif
                <div class="form-group row">
                    <label for="staticEmail" class="col-xl-3 col-lg-4 col-md-8 col-sm-12 col-form-label text-muted">Ongkos Kirim</label>
                    <div class="col-sm-9">
                      <input type="text" disabled value=": {{rupiah($transaction->shipping_fee)}}" class="form-control-plaintext">
                    </div>
                </div>
                {{-- Detail Pesanan --}}
                <div class="form-group row">
                  <label for="staticEmail" class="col-xl-3 col-lg-4 col-md-8 col-sm-12 col-form-label">Detail Pesanan</label>
                </div>
                @foreach ($transaction->item as $item)
                  <div class="form-group row">
                    <label for="staticEmail" class="col-xl-3 col-lg-4 col-md-8 col-sm-12 col-form-label text-muted">Nama Item</label>
                    <div class="col-sm-9">
                      <input type="text" disabled value=": {{$item->product? $item->product->name : 'produk dihapus'}}" class="form-control-plaintext">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="staticEmail" class="col-xl-3 col-lg-4 col-md-8 col-sm-12 col-form-label text-muted">Quantity</label>
                    <div class="col-sm-9">
                      <input type="text" disabled value=": {{$item->qty}} * {{rupiah($item->price)}}" class="form-control-plaintext">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="staticEmail" class="col-xl-3 col-lg-4 col-md-8 col-sm-12 col-form-label text-muted">Total Harga per Item</label>
                    <div class="col-sm-9">
                      <input type="text" disabled value=": {{rupiah($item->price)}}" class="form-control-plaintext">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="staticEmail" class="col-xl-3 col-lg-4 col-md-8 col-sm-12 col-form-label text-muted">Subtotal Pesanan</label>
                    <div class="col-sm-9">
                      <input type="text" disabled value=": {{rupiah($item->qty * $item->price)}}" class="form-control-plaintext">
                    </div>
                  </div>
                @endforeach
                {{-- DETAIL VOUCHER --}}
                @if ($transaction->voucher_id != null) 
                  <div class="form-group row">
                    <label for="staticEmail" class="col-xl-3 col-lg-4 col-md-8 col-sm-12 col-form-label">Detail Voucher</label>
                  </div>
                  <div class="form-group row">
                    <label for="staticEmail" class="col-xl-3 col-lg-4 col-md-8 col-sm-12 col-form-label text-muted">Judul Voucher</label>
                    <div class="col-sm-9">
                      <input type="text" disabled value=": {{$transaction->voucher->title}}" class="form-control-plaintext">
                    </div>
                  </div>
  
                  <div class="form-group row">
                    <label for="staticEmail" class="col-xl-3 col-lg-4 col-md-8 col-sm-12 col-form-label text-muted">Tipe Voucher</label>
                    <div class="col-sm-9">
                      @if ($transaction->voucher->discount_type == 'fixed')    
                        <input type="text" disabled value=": {{rupiah($transaction->voucher->discount_value)}}" class="form-control-plaintext">
                      @else
                        <input type="text" disabled value=": {{$transaction->voucher->discount_value}}%" class="form-control-plaintext">
                      @endif
                      
                    </div>
                  </div>
                @endif
                {{-- DETAIL FINISH --}}
                @if ($transaction->voucher_id != null)
                <div class="form-group row">
                  <label for="staticEmail" class="col-xl-3 col-lg-4 col-md-8 col-sm-12 col-form-label text-muted">Total Bayar</label>
                  <div class="col-sm-9">
                    <input type="text" disabled value=": {{rupiah($transaction->getTotalDiscountVoucherAttribute())}}" class="form-control-plaintext">
                  </div>
                </div>
                
                @else

                <div class="form-group row">
                  <label for="staticEmail" class="col-xl-3 col-lg-4 col-md-8 col-sm-12 col-form-label text-muted">Total Bayar</label>
                  <div class="col-sm-9">
                    
                    <input type="text" disabled value=": {{rupiah($transaction->total_belanja + $transaction->shipping_fee)}}" class="form-control-plaintext">
                  </div>
                </div>
                @endif
                <div class="form-group row">
                  <label for="staticEmail" class="col-xl-3 col-lg-4 col-md-8 col-sm-12 col-form-label text-muted">Catatan Pengiriman</label>
                  <div class="col-sm-9">
                    <input type="text" name="" id="" readonly class="form-control-plaintext" value=": {{$transaction->note}}">
                  </div>
                </div>
                <div class="form-group row">
                  <label for="staticEmail" class="col-xl-3 col-lg-4 col-md-8 col-sm-12 col-form-label text-muted">Jenis Pembayaran</label>
                  <div class="col-sm-9">
                    <input readonly disabled type="text" value=": {{strtoupper($transaction->payment_type)}}"
                            class="form-control-plaintext">
                  </div>
                </div>
                <div class="form-group row">
                  <label for="staticEmail" class="col-xl-3 col-lg-4 col-md-8 col-sm-12 col-form-label text-muted">Status Transaksi</label>
                  <div class="col-sm-9">
                    @if ($transaction->payment_type == 'manual' && $transaction->status_int == 0)
                    <input readonly disabled type="text" value=": Pembayaran Manual"
                            class="form-control-plaintext">
                    @else
                    <input readonly disabled type="text" value=": {{$transaction->status_transaction}}"
                            class="form-control-plaintext">
                    @endif
                  </div>
                </div>
                
              </div>
                @include('transaction.button-transaction')
                <div class="row mb-3 mt-2">
                  <button type="button" id="btnResetEditUser" class="btn btn-reset ml-2 mr-4"><a class="click-edit" href="{{route('get.transaction')}}">Kembali</a></button>
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
  @include('transaction.modal-transaction')
@endsection
@section('script')
    <script>
      function changeStatus(status) {
        if(status == '2') {
          $("#modalStatusKonfirmasi").modal('show');
        } else if (status == '3') {
          $("#modalStatusProses").modal('show');
        } else if (status == '4') {
          $("#modalToStatusProses").modal('show');
        } else if (status == '5') {
          $("#modalToStatusSelesai").modal('show')
        } else if (status == '6') {
          $("#modalStatusBatalkan").modal('show')
        }
      }
    </script>
@endsection