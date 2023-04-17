<div class="col-xl-3 col-lg-12 col-md-12 col-sm-12">
  <div class="card card-custom" style="height: 68vh;">
    <div class="card-header" style="background: #FFFFFF !important; border: none;"><h5>Status Transaksi</h5></div>
    {{-- 0: menunggu pembayaran, 1:Pembayaran COD, 2:Menunggu Konfirmasi, 3:Pesanan Diproses, 4:Pesanan Dikirim, 5:Pesanan Selesai, 6:Pesanan Dibatalkan --}}
    <div class="card-body">
      <div class="col-md-12">
        @if ($transaction->status_int < 2)    
          @if ($transaction->payment_type == 'manual')
            <button style="{{$transaction->status_int == 6 ? 'opacity: 54%' : ''}}" onclick="{{$transaction->status_int < 2 ? 'changeStatus("2")' : '' }}"   class="btn btn-danger mb-2 {{$transaction->status_int > 0 && $transaction->status_int < 6 ? 'btn-radius-green' : 'btn-radius-red'}}" type="button">{{$transaction->status_int == 0 && $transaction->status_int < 6? 'Pembayaran Manual' : ($transaction->status_int == 1 ? 'Pembayaran Manual' : ($transaction->status_int < 6 ? 'Pembayaran Berhasil' : 'Pembayaran Manual Gagal'))}}</button>
          @else
            <button style="{{$transaction->status_int == 6 ? 'opacity: 54%' : ''}}" onclick="{{$transaction->status_int < 2 ? 'changeStatus("2")' : '' }}"  class="btn btn-danger mb-2 {{$transaction->status_int > 0 && $transaction->status_int < 6 ? 'btn-radius-green' : 'btn-radius-red'}}" type="button">{{$transaction->status_int == 0 && $transaction->status_int < 6 ? 'Menunggu Pembayaran' : ($transaction->status_int == 1 ? 'Pembayaran COD' : ($transaction->status_int < 6 ? 'Pembayaran Berhasil' : 'Pembayaran COD Gagal'))}}</button>
          @endif
        @else
          <button style="{{$transaction->status_int == 6 ? 'opacity: 54%' : ''}}"  class="btn btn-danger mb-2 {{$transaction->status_int > 0 && $transaction->status_int < 6 ? 'btn-radius-green' : 'btn-radius-red'}}" type="button">{{$transaction->status_int == 0 && $transaction->status_int < 6? 'Pembayaran Manual' : ($transaction->status_int == 1 ? 'Pembayaran Manual' : ($transaction->status_int < 6 ? 'Pembayaran Berhasil' : 'Pembayaran Manual Gagal'))}}</button>
        @endif


        @if ($transaction->status_int == 2)
          <button onclick="changeStatus('3')" class="btn btn-danger mb-2 {{$transaction->status_int > 2 && $transaction->status_int < 6 ? 'btn-radius-green' : 'btn-radius-red'}}" type="button">{{$transaction->status_int > 2 && $transaction->status_int < 6 ? ' Konfirmasi Berhasil' : 'Menunggu Konfirmasi'}}</button>
        @else
          @if ($transaction->status_int == 0) 
          <button style="opacity: 54%" class="btn btn-danger mb-2 {{$transaction->status_int > 2 && $transaction->status_int < 6 ? 'btn-radius-green' : 'btn-radius-red'}}" type="button">{{$transaction->status_int > 2 && $transaction->status_int < 6 ? ' Konfirmasi Berhasil' : 'Menunggu Konfirmasi'}}</button>
          @else
          <button style="{{$transaction->status_int == 6 ? 'opacity: 54%' : ''}}" class="btn btn-danger mb-2 {{$transaction->status_int > 2 && $transaction->status_int < 6 ? 'btn-radius-green' : 'btn-radius-red'}}" type="button">{{$transaction->status_int > 2 && $transaction->status_int < 6 ? ' Konfirmasi Berhasil' : 'Menunggu Konfirmasi'}}</button>
          @endif
        @endif

        @if ($transaction->status_int == 3)
          <button onclick="changeStatus('4')" class="btn btn-danger mb-2 {{$transaction->status_int > 3 && $transaction->status_int < 6 ? 'btn-radius-green' : 'btn-radius-red'}}" type="button">{{$transaction->status_int > 3 && $transaction->status_int < 6 ? 'Pesanan Sudah Diproses' : 'Pesanan Diproses'}}</button>
        @else
          @if ($transaction->status_int == 2 || $transaction->status_int == 1 || $transaction->status_int == 0)
          <button style="opacity: 54%" class="btn btn-danger mb-2 {{$transaction->status_int > 3 && $transaction->status_int < 6 ? 'btn-radius-green' : 'btn-radius-red'}}" type="button">{{$transaction->status_int > 3 && $transaction->status_int < 6 ? 'Pesanan Sudah Diproses' : 'Pesanan Diproses'}}</button>
          @else
          <button style="{{$transaction->status_int == 6 ? 'opacity: 54%' : ''}}"  class="btn btn-danger mb-2 {{$transaction->status_int > 3 && $transaction->status_int < 6 ? 'btn-radius-green' : 'btn-radius-red'}}" type="button">{{$transaction->status_int > 3 && $transaction->status_int < 6 ? 'Pesanan Sudah Diproses' : 'Pesanan Diproses'}}</button>
          @endif
        @endif

        @if ($transaction->status_int == 4)
          <button onclick="changeStatus('5')" class="btn btn-danger mb-2 {{$transaction->status_int > 4 && $transaction->status_int < 6 ? 'btn-radius-green' : 'btn-radius-red'}}" type="button">{{$transaction->status_int > 4 && $transaction->status_int < 6 ? 'Pesanan Dikirim' : 'Pengiriman Pesanan'}}</button>
        @else
          @if ($transaction->status_int == 3 || $transaction->status_int == 2 || $transaction->status_int == 1 || $transaction->status_int == 0)
          <button style="opacity: 54%" class="btn btn-danger mb-2 {{$transaction->status_int > 4 && $transaction->status_int < 6 ? 'btn-radius-green' : 'btn-radius-red'}}" type="button">{{$transaction->status_int > 4 && $transaction->status_int < 6 ? 'Pesanan Dikirim' : 'Pengiriman Pesanan'}}</button>
          @else
          <button style="{{$transaction->status_int == 6 ? 'opacity: 54%' : ''}}"  class="btn btn-danger mb-2 {{$transaction->status_int > 4 && $transaction->status_int < 6 ? 'btn-radius-green' : 'btn-radius-red'}}" type="button">{{$transaction->status_int > 4 && $transaction->status_int < 6 ? 'Pesanan Dikirim' : 'Pengiriman Pesanan'}}</button>
          @endif
        @endif

        @if ($transaction->status_int == 4 || $transaction->status_int == 3 || $transaction->status_int == 2 || $transaction->status_int == 1 || $transaction->status_int == 0)
        <button style="opacity: 54%" class="btn btn-danger mb-2 {{$transaction->status_int == 5 ? 'btn-radius-green' : 'btn-radius-red'}}" type="button">{{$transaction->status_int == 5 ? 'Pesanan Selesai' : 'Pesanan Selesai'}}</button>    
        @else
        <button style="{{$transaction->status_int == 6 ? 'opacity: 54%' : ''}}"  class="btn btn-danger mb-2 {{$transaction->status_int == 5 ? 'btn-radius-green' : 'btn-radius-red'}}" type="button">{{$transaction->status_int == 5 ? 'Pesanan Selesai' : 'Pesanan Selesai'}}</button>    

        @endif

        <button style="{{$transaction->status_int != 6 ? 'opacity: 53%;' : ''}}" class="btn btn-danger mb-2 btn-radius-red" type="button">{{$transaction->status_int == 6 ? 'Pesanan Dibatalkan' : 'Pesanan Dibatalkan'}}</button>
      </div>
    </div>
  </div>
</div>