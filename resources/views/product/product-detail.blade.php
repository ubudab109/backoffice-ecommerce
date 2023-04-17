@extends('main.main')
@section('content')
@include('main.topbar')

<div class="row mt-3">
  <div class="col-md-12">
    <div class="card card-custom">
      <div class="row">
        <div class="col-md-6">
          <p class="title-text pl-2">Detail Product</p>
        </div>
      </div>
      <hr class="hr-custom">
      {{-- <div class="row justify-content-center">
        @foreach ($product->files()->get() as $item)
        <div class="col-md-4 text-center">
          <img src="{{$item->files}}" alt="" style="width: 50%">
        </div>
        @endforeach
      </div> --}}




      <div class="row">
        <div class="col-md-12">

          <form class="form-custom form stacked" id="formEditProduct" ajax="true" files="true" multipleFiles="true">
            <div class="form-group row">
              <label for="staticEmail" class="col-sm-2 col-form-label">Kode Product</label>
              <div class="col-sm-5">
                <input type="text" value=": {{$product->code_product}}" readonly class="form-control-plaintext" placeholder="Masukan Kode Produk" name="codeProduct" id="codeProduct">
              </div>
            </div>

            <div class="form-group row">
              <label for="staticEmail" class="col-sm-2 col-form-label">Nama</label>
              <div class="col-sm-5">
                <input type="text" readonly name="productName" value=": {{$product->name}}" id="productName" class="form-control-plaintext"
                placeholder="Masukkan Title Product">
              </div>
            </div>

            <div class="form-group row">
              <label for="staticEmail" class="col-sm-2 col-form-label">Kategori Product</label>
              <div class="col-sm-5">
                <input type="text" class="form-control-plaintext" value=": {{$product->category->name}}">
              </div>
            </div>

            <div class="form-group row">
              <label for="staticEmail" class="col-sm-2 col-form-label">Tag</label>
              <div class="col-sm-5">
                <input type="text" readonly name="tags" value=": {{$product->tag != null ? $product->tag->tag_name : ''}}" id="tags" class="form-control-plaintext"
                placeholder="Masukkan Tag Product">
              </div>
            </div>

            <div class="form-group row">
              <label for="staticEmail" class="col-sm-2 col-form-label">Harga</label>
              <div class="col-sm-5">
                <input id="priceValueProduct" readonly value="{{$product->price}}" name="priceProduct" type="hidden" class="form-control-plaintext">
                <input id="valuePriceProduct" readonly value=": Rp. {{rupiahFormat($product->price)}}" name="valuePriceProduct" type="text" class="form-control-plaintext">
              </div>
            </div>

            <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Foto</label>
                <div class="col-sm-5">
                  <div id="image" class="row">
                    @foreach ($product->files()->get() as $item)
                      <img src="{{$item->files}}" alt="" width="100" class="mr-2 mb-2">
                    @endforeach
                  </div>
              </div>
            </div>

          <div class="form-group row">
            <label for="staticEmail" class="col-sm-2 col-form-label">Detail Produk</label>
            <div class="col-sm-5">
              <input type="text" class="form-control-plaintext" readonly name="description" id="description" value=": {!! $product->description !!}">
            </div>
          </div>

          <div class="form-group row">
            <label for="staticEmail" class="col-sm-2 col-form-label">Status Produk</label>
            <div class="col-sm-5">
                <input readonly type="text" value=":  {{$product->status == '1' ? 'Aktif' : 'Tidak Aktif'}}" id="" class="form-control-plaintext">
            </div>
          </div>

          <div class="form-group row">
            <label for="promoStatus" class="col-sm-2 col-form-label">Status Promo</label>
            <div class="col-sm-5">
              <input readonly type="text" value=":  {{$product->promo_status == '1' ? 'Aktif' : 'Tidak Aktif'}}" id="" class="form-control-plaintext">
            </div>
          </div>

          @if ($product->promo_status == '1')    
            <div class="form-group row" id="formPromoType">
              <label for="promoType" class="col-sm-2 col-form-label">Tipe Promo</label>
              <div class="col-sm-5">
                <div class="with-error">
                  <select readonly disabled name="promoType" id="promoType" class="form-select select2">
                    <option value="">Tipe Promo</option>
                    <option value="fixed" {{$product->promo_type == 'fixed' ? 'selected' : ''}}>Harga</option>
                    <option value="discount" {{$product->promo_type == 'discount' ? 'selected' : ''}}>Diskon</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="form-group row" id="formPromoValue">
              <label for="promoValueInput" class="col-sm-2 col-form-label">Potongan Promo</label>
              <div class="col-sm-5">
                <input readonly disabled type="hidden" name="promoValue" value="{{$product->promo_value}}" id="promoValue" class="form-control">

                @if ($product->promo_type == 'fixed')
                  <input readonly disabled value="{{rupiah($product->promo_value)}}" type="text" name="promoValueInput" id="promoValueInput"
                    class="form-control">
                @elseif($product->promo_type == 'discount')
                  <input readonly disabled value="{{$product->promo_value}}%" type="text" name="promoValueInput" id="promoValueInput"
                    class="form-control">
                @endif
              </div>
            </div>

            <div class="form-group row" id="formTotalPrice">
              <label for="promoValueInput" class="col-sm-2 col-form-label">Total Harga</label>
              <div class="col-sm-5">
                <input readonly value="{{rupiah($product->promo_price)}}" type="text" name="priceProducts" id="priceProduct" class="form-control">
              </div>
            </div>

            
          @endif
          <div class="row mb-3">
            <button type="button" class="btn btn-reset ml-3 mr-4 mb-3"><a class="click-edit" href="{{route('get.edit-detail.product', $product->id)}}">Edit</a></button>
          </div>
        </div>
        </form>
      </div>
    </div>
  </div>
</div>
</div>

@endsection