@extends('main.main')
@section('content')
@include('main.topbar')

<div class="row mt-3">
  <div class="col-md-12">
    <div class="card card-custom">
      <div class="row">
        <div class="col-md-6">
          <p class="title-text pl-2">Edit Product</p>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <form action="{{route('post.edit.product')}}" class="form-custom form stacked" method="POST"
            id="formEditProduct" ajax="true" files="true" multipleFiles="true">

            <input type="hidden" name="productId" value="{{$product->id}}" id="productId" class="form-control">

            <div class="form-group row">
              <label for="staticEmail" class="col-sm-2 col-form-label">Kode Product*</label>
              <div class="col-sm-5">
                <input type="text" value="{{$product->code_product}}" readonly class="form-control"
                  placeholder="Masukan Kode Produk" name="codeProduct" id="codeProduct">
              </div>
            </div>

            <div class="form-group row">
              <label for="staticEmail" class="col-sm-2 col-form-label">Nama*</label>
              <div class="col-sm-5">
                <input type="text" name="productName" value="{{$product->name}}" id="productName" class="form-control"
                  placeholder="Masukkan Title Product">
              </div>
            </div>

            <div class="form-group row">
              <label for="staticEmail" class="col-sm-2 col-form-label">Kategori Product*</label>
              <div class="col-sm-5">
                <select name="categoryId" id="categoryId" class="form-control">
                  <option value="" selected>Pilih Kategori Produk</option>
                  @foreach ($categories as $category)
                  <option {{$category->id == $product->category_id ? 'selected' : ''}} value="{{$category->id}}"
                    data-code="{{$category->code_category}}">
                    {{$category->code_category}}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="form-group row">
              <label for="staticEmail" class="col-sm-2 col-form-label">Tag*</label>
              <div class="col-sm-5">
                <input type="text" name="tags" value="{{$product->tag != null ? $product->tag->tag_name : ''}}"
                  id="tags" class="form-control" placeholder="Masukkan Tag Product">
              </div>
            </div>

            <div class="form-group row">
              <label for="staticEmail" class="col-sm-2 col-form-label">Harga*</label>
              <div class="col-sm-5">
                <input id="priceValueProduct" value="{{$product->price}}" name="priceProduct" type="hidden"
                  class="form-control">
                <input id="valuePriceProduct" value="Rp. {{rupiahFormat($product->price)}}" name="valuePriceProduct"
                  type="text" class="form-control">
              </div>
            </div>

            <div class="form-group row">
              <label for="staticEmail" class="col-sm-2 col-form-label">Foto*</label>
              <div class="col-sm-5">
                <div id="image" class="row">
                  @foreach ($product->files()->get() as $item)

                  <div id="preview-{{$item->id}}" class="card" style="width: 100px;
                      padding: 5px;
                      border: 1px solid #D9D9D9;
                      border-radius: 10px;">
                    <div style="position: absolute;right: 0; cursor: pointer;" onclick="deleteImage({{$item->id}})">
                      <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g filter="url(#filter0_d_117_2616)">
                          <path
                            d="M21.414 20L24.243 17.172C24.4306 16.9844 24.5361 16.7299 24.5361 16.4645C24.5361 16.1991 24.4306 15.9446 24.243 15.757C24.0554 15.5694 23.8009 15.4639 23.5355 15.4639C23.2701 15.4639 23.0156 15.5694 22.828 15.757L20 18.586L17.172 15.757C16.9844 15.5694 16.7299 15.4639 16.4645 15.4639C16.1991 15.4639 15.9446 15.5694 15.757 15.757C15.5694 15.9446 15.4639 16.1991 15.4639 16.4645C15.4639 16.7299 15.5694 16.9844 15.757 17.172L18.586 20L15.757 22.828C15.5694 23.0156 15.4639 23.2701 15.4639 23.5355C15.4639 23.8009 15.5694 24.0554 15.757 24.243C15.9446 24.4306 16.1991 24.5361 16.4645 24.5361C16.7299 24.5361 16.9844 24.4306 17.172 24.243L20 21.414L22.828 24.243C23.0156 24.4306 23.2701 24.5361 23.5355 24.5361C23.8009 24.5361 24.0554 24.4306 24.243 24.243C24.4306 24.0554 24.5361 23.8009 24.5361 23.5355C24.5361 23.2701 24.4306 23.0156 24.243 22.828L21.414 20ZM20 30C14.477 30 10 25.523 10 20C10 14.477 14.477 10 20 10C25.523 10 30 14.477 30 20C30 25.523 25.523 30 20 30Z"
                            fill="#D9D9D9" />
                        </g>
                        <defs>
                          <filter id="filter0_d_117_2616" x="0" y="0" width="40" height="40"
                            filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                            <feFlood flood-opacity="0" result="BackgroundImageFix" />
                            <feColorMatrix in="SourceAlpha" type="matrix"
                              values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha" />
                            <feOffset />
                            <feGaussianBlur stdDeviation="5" />
                            <feComposite in2="hardAlpha" operator="out" />
                            <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.6 0" />
                            <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_117_2616" />
                            <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_117_2616"
                              result="shape" />
                          </filter>
                        </defs>
                      </svg>

                    </div>
                    <img class="card-img-top" src="{{$item->files}}" alt="Card image cap">
                  </div>
                  @endforeach
                </div>
                <div class="custom-file d-flex flex-row-reverse">
                  <input type="file" name="file" class="custom-file-input" id="file_upload"
                  multiple />
                  <label class="custom-file-label text-right" for="customFile"></label>
                </div>
                <span class="text-red" id="error-img"></span>
                <p class="text-muted">*Ukuran Foto : 600 x 600 px | Maks 5 Foto</p>
              </div>
            </div>

            <div class="form-group row">
              <label for="staticEmail" class="col-sm-2 col-form-label">Detail Produk*</label>
              <div class="col-sm-5">
                <textarea class="form-control" name="description"
                  id="description">{!! $product->description !!}</textarea>
              </div>
            </div>

            <div class="form-group row">
              <label for="staticEmail" class="col-sm-2 col-form-label">Status Produk</label>
              <div class="custom-control custom-switch ml-3">
                <input type="checkbox" name="flagStatus" id="flagStatus" {{$product->status == '1' ? 'checked' : ''}}
                class="custom-control-input">
                <label class="custom-control-label" for="flagStatus"></label>
              </div>
            </div>

            {{-- <div class="form-group row">
              <label for="promoStatus" class="col-sm-2 col-form-label">Status Promo</label>
              <div class="custom-control custom-switch ml-3">
                <input type="checkbox" name="promoStatus" id="promoStatus" {{$product->promo_status == '1' ? 'checked' :
                ''}} class="custom-control-input">
                <label class="custom-control-label" for="promoStatus"></label>
              </div>
            </div> --}}

            @if ($product->promo_status == '1')
            <div class="form-group row" id="formPromoType">
              <label for="promoType" class="col-sm-2 col-form-label">Tipe Promo*</label>
              <div class="col-sm-5">
                <div class="with-error">
                  <select name="promoType" id="promoType" class="form-select select2">
                    <option value="">Tipe Promo</option>
                    <option value="fixed" {{$product->promo_type == 'fixed' ? 'selected' : ''}}>Harga</option>
                    <option value="discount" {{$product->promo_type == 'discount' ? 'selected' : ''}}>Diskon</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="form-group row" id="formPromoValue">
              <label for="promoValueInput" class="col-sm-2 col-form-label">Potongan Promo*</label>
              <div class="col-sm-5">
                <input type="hidden" name="promoValue" value="{{$product->promo_value}}" id="promoValue"
                  class="form-control">

                @if ($product->promo_type == 'fixed')
                <input value="{{rupiah($product->promo_value)}}" type="text" name="promoValueInput" id="promoValueInput"
                  class="form-control">
                @elseif($product->promo_type == 'discount')
                <input value="{{$product->promo_value}}%" type="text" name="promoValueInput" id="promoValueInput"
                  class="form-control">
                @endif
              </div>
            </div>

            <div class="form-group row" id="formTotalPrice">
              <label for="promoValueInput" class="col-sm-2 col-form-label">Total Harga</label>
              <div class="col-sm-5">
                <input readonly value="{{rupiah($product->promo_price)}}" type="text" name="priceProducts"
                  id="priceProduct" class="form-control">
              </div>
            </div>
            @endif

            <div class="row mb-3">
              <button type="button" id="btnResetEditProduct" class="btn btn-reset ml-2 mr-4">Reset</button>
              <button type="button" id="btnEditProduct" class="btn btn-save">Simpan</button>
            </div>
            {{-- --}}

        </div>
        </form>
      </div>
    </div>
  </div>
</div>
</div>

@endsection

@section('modal')
<div class="modal fade" id="modalEditProduct" aria-labelledby="modalEditProductLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-custom">
    <div class="modal-content content-footer">
      <div class="modal-body">
        <center>
          <img src="{{asset('image/icon-add-customer.svg')}}" class="mt-3">
          <p class="title-notif" id="titleNotif">Kamu akan mengubah Product ?</p>
          <p class="content-notif" id="contentNotif">Kamu akan mengubah Product, <br> apakah kamu yakin ?</p>
        </center>
      </div>
      <div class="modal-footer">
        <div class="row">
          <div class="col-md-12" id="btnNotif">
            <form action="{{route('post.edit.product')}}" class="form-custom form stacked" method="POST"
              id="formEditProduct" ajax="true" files="true" multipleFiles="true">
              <button type="button" data-dismiss="modal" aria-label="Close" class="btn-notif-cancel"
                id="">Batalkan</button>
              <button type="submit" class="btn-notif" id="submitProduct">Lanjutkan</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('script')
<script>

function deleteImage(idImage) {
    let url = "/product/delete-files/"+idImage;
    // url = url.replace(':id',idImage);
    $.ajax({
      headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
      url: url,
      type: 'DELETE',
      success: (res) => {
        $(`#preview-${idImage}`).remove();
      }
    });
  }

  $('#file_upload').on('change', function(e) {
    let url = "{{route('post.file.product',':id')}}";
    url = url.replace(':id',$("#productId").val());

    e.preventDefault();
    var formData = new FormData();
    let TotalFiles = $("#file_upload")[0].files.length; //total files
    let files = $("#file_upload")[0];
    for (let i = 0; i < TotalFiles; i++) {
      formData.append(`file[${i}]`, files.files[i]);
    }
    $.ajax({
      headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
      type: 'POST',
      url: url,
      data: formData,
      dataType    : 'json',
      processData: false,
      contentType: false,
      enctype: 'multipart/form-data',
      success: (res) => {
        let html = '';
        for (let j = 0; j < res.length; j++) {
          html += `
          <div id="preview-${res[j]['id']}" class="card" style="width: 100px;
                padding: 5px;
                border: 1px solid #D9D9D9;
                border-radius: 10px;">
              <div
                style="position: absolute;right: 0; cursor: pointer;"
                onclick="deleteImage(${res[j]['id']})"
              >
                <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g filter="url(#filter0_d_117_2616)">
                <path d="M21.414 20L24.243 17.172C24.4306 16.9844 24.5361 16.7299 24.5361 16.4645C24.5361 16.1991 24.4306 15.9446 24.243 15.757C24.0554 15.5694 23.8009 15.4639 23.5355 15.4639C23.2701 15.4639 23.0156 15.5694 22.828 15.757L20 18.586L17.172 15.757C16.9844 15.5694 16.7299 15.4639 16.4645 15.4639C16.1991 15.4639 15.9446 15.5694 15.757 15.757C15.5694 15.9446 15.4639 16.1991 15.4639 16.4645C15.4639 16.7299 15.5694 16.9844 15.757 17.172L18.586 20L15.757 22.828C15.5694 23.0156 15.4639 23.2701 15.4639 23.5355C15.4639 23.8009 15.5694 24.0554 15.757 24.243C15.9446 24.4306 16.1991 24.5361 16.4645 24.5361C16.7299 24.5361 16.9844 24.4306 17.172 24.243L20 21.414L22.828 24.243C23.0156 24.4306 23.2701 24.5361 23.5355 24.5361C23.8009 24.5361 24.0554 24.4306 24.243 24.243C24.4306 24.0554 24.5361 23.8009 24.5361 23.5355C24.5361 23.2701 24.4306 23.0156 24.243 22.828L21.414 20ZM20 30C14.477 30 10 25.523 10 20C10 14.477 14.477 10 20 10C25.523 10 30 14.477 30 20C30 25.523 25.523 30 20 30Z" fill="#D9D9D9"/>
                </g>
                <defs>
                <filter id="filter0_d_117_2616" x="0" y="0" width="40" height="40" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                <feOffset/>
                <feGaussianBlur stdDeviation="5"/>
                <feComposite in2="hardAlpha" operator="out"/>
                <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.6 0"/>
                <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_117_2616"/>
                <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_117_2616" result="shape"/>
                </filter>
                </defs>
                </svg>
  
              </div>
              <img class="card-img-top" src="${res[j]['files']}"  alt="Card image cap">
          </div>
          `
        }
        $("#image").append(html);
      }
    })
  });

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

  $("#categoryId").change(() => {
    var code = $("#categoryId option:selected").data('code');
    $("#categoryCode").text(code+'-')
  });

  $("#valuePriceProduct").keyup(() => {
    var price = $("#valuePriceProduct").val();
    $("#valuePriceProduct").val(formatRupiahRp(price));
    var splitedRp = price.split('Rp.');
    var splittedPrice = splitedRp[1].split('.');
    var test = document.getElementById('priceValueProduct').value = splittedPrice.join('');
  });
</script>
@endsection