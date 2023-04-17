@extends('main.main')
@section('content')
@include('main.topbar')

<div class="row mt-3">
  <div class="col-md-12">
    <div class="card card-custom">
      <div class="row">
        <div class="col-md-6">
          <p class="title-text pl-2">Add Inventory</p>
        </div>
      </div>
      <hr class="hr-custom">
      <div class="row">
        <div class="col-md-12">
          <form action="{{route('post.add.inventory')}}" class="form-custom form stacked" method="POST"
            id="formAddInventory" ajax="true">

            <div class="tmpForm">
              <div class="form-row p-0 form_stacked">
                <div class="form-group col">
                  <label for="inputEmail4">Nama Produk*</label>
                  <select required class="form-control" name="productId[]" id="productId">
                    <option value="" selected>Pilih Produk</option>
                    @foreach ($products as $product)
                    <option value="{{$product->id}}" data-code="{{$product->code_product}}">{{$product->name}}
                    </option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group col">
                  <label for="inputPassword4">Total Inventory*</label>
                  <input required type="number" name="totalInventory[]" id="totalInventory" class="form-control">
                </div>
                <div class="form-group col">
                  <label for="inputPassword4">Note</label>
                  <input type="text" name="note[]" id="note" class="form-control">
                </div>
                <button type="button" class="btn btn-danger remove_form d-none" style="clip-path: circle()">-</button>
                <button type="button" onclick="cloneForm()" class="btn btn-success add_form" style="clip-path: circle()">+</button>
              </div>
            </div>

            <div class="row mb-3">
                <button type="button" id="btnResetAddInventory" class="btn btn-reset ml-2 mr-4">Reset</button>
                <button type="button" id="btnAddInventory" class="btn btn-save">Simpan</button>
            </div>
            
            {{-- <div class="form_stacked">
              <div class="row p-0 clone_form">
                <div class="col-md-12 mt-2">
                  <div class="form-group">
                    <label for="">Nama Produk</label>
                    <div class="with-error">
                      <select required class="form-control" name="productId[]" id="productId">
                        <option value="" selected>Pilih Produk</option>
                        @foreach ($products as $product)
                        <option value="{{$product->id}}" data-code="{{$product->code_product}}">{{$product->name}}
                        </option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-md-12 mt-2">
                  <div class="form-group">
                    <label for="">Total Inventory</label>
                    <div class="with-error">
                      <input required type="text" name="totalInventory[]" id="totalInventory" class="form-control">
                    </div>
                  </div>
                </div>

              </div>
              <div class="col-md-12 mt-2 d-none">
                <button type="button" class="btn btn-danger remove_form">-</button>
              </div>
              <hr class="hr-card">
            </div>
            <div class="row-mb-3">
              <div class="col-md-12 mt-2 text-right">
                <button type="button" class="btn btn-primary add_form">+</button>
              </div>
            </div>
            <hr class="hr-card">
            <div class="row mb-3">
              <div class="col-md-12">
                <button type="button" id="btnResetAddInventory" class="btn-warning">Reset</button>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-12">
                <button type="button" id="btnAddInventory" class="btn-next">Add Inventory</button>
                <button type="button" class="btn-back"><a href="{{route('get.inventory')}}">Kembali</a></button>
              </div>
            </div> --}}
        </div>
        </form>
      </div>
    </div>
  </div>
</div>
</div>

@endsection

@section('modal')
<div class="modal fade" id="modalAddInventory" aria-labelledby="modalAddPrAddInventoryLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-custom">
    <div class="modal-content content-footer-status">
      <div class="modal-body">
        <center>
          <p class="title-notif" id="titleNotif">Kamu akan menambahkan Inventory ?</p>
        </center>
        <center>
          <div class="row">
            <div class="col-md-12" id="btnNotif">
              <form action="{{route('post.add.inventory')}}" class="form-custom form stacked" method="POST"
                id="formAddInventory" ajax="true">
                <button class="btn btn-success btn-status" type="submit" class="btn-notif">Yes</button>
                <button class="btn btn-danger btn-status" type="button" data-dismiss="modal" aria-label="Close" class="btn-notif-cancel"
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

@section('script')
<script>
  function cloneForm() {
      var $clone = $(".form_stacked:first").clone().find("input").val("").end().insertAfter('.form_stacked:last');
      // $clone.find('div').removeClass('d-none');
      $(".form_stacked").append($clone);
      $('.form_stacked:last').find('button').removeClass('d-none');
      // $(".form_stacked:first").find('button').addClass('d-none');

    }
  $(document).ready(function() {
    
    // $(".add_form").click(() => {
    //   console.log('work');
    //   $(".form_stacked:first").find('button').addClass('d-none')
    //   var $clone = $(".form_stacked:first").clone().insertAfter('.form_stacked:last');
    //   $clone.find('div').removeClass('d-none');
    //   $(".form_stacked").append($clone);
    //   $('.form_stacked:last').find('button').removeClass('d-none');
    // })

    $(document).on("click", ".remove_form", function() {
        // $(this).closest(".form_stacked:bef").find('.add_form').removeClass('d-none')
        // console.log($(".form-stacked").length)
        $(this).closest(".form_stacked").remove();
    });
  });
  
</script>
@endsection

{{-- @section('script')
<script>
  function uploadFiles() {
    $("div#image").html('');
    var files = document.getElementById('file_upload').files;
    var filenames="";
    var preview = $("#image");
    for(var i=0;i<files.length;i++){
      filenames+=files[i].name+"\n";
      var reader = new FileReader();
      reader.onload = function(event) {
        $($.parseHTML('<img style="width: 15%;">')).attr('src', event.target.result).appendTo('div#image');
      }
      reader.readAsDataURL(files[i]);
      
    }
  }
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
  $("#promoStatus").change(() => {
    var status = $("#promoStatus option:selected").val();
    if (status == '1') {
      $("#formPromoType").removeClass('d-none');
      $("#formPromoValue").removeClass('d-none');
      $("#formTotalPrice").removeClass('d-none');
    } else {
      $("#formPromoType").addClass('d-none');
      $("#formPromoValue").addClass('d-none');
      $("#formTotalPrice").addClass('d-none');
    }
  });
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

  $("#promoType").change(() => {
    var typePromo  = $("#promoType option:selected").val();
    if (typePromo !== '') {
      $("#promoValueInput").removeAttr('disabled');

    } else {
      $("#promoValueInput").attr('disabled', true);
    }
  });

  $("#promoValueInput").keyup(() => {
    if ($("#valuePriceProduct").val() == '') {
      document.getElementById('promoValue').value = ''
      document.getElementById('promoValueInput').value = ''
    }
    var typePromo  = $("#promoType option:selected").val();
    var valuePromo = $('#promoValueInput');
    var priceProduct= $('#priceValueProduct').val();
    var totalPromo = $("#priceProduct");
    if (typePromo == 'fixed') {
      var price = formatRupiahRp(valuePromo.val());
      $("#promoValueInput").val(price);
      var splitedRp = price.split('Rp.');
      var splittedPrice = splitedRp[1].split('.');
      document.getElementById('promoValue').value = splittedPrice.join('');
      var calculated = parseInt(priceProduct) - parseInt(splittedPrice.join(''));
      if (calculated < 0) {
        document.getElementById('priceProduct').value = formatRupiahRp('0');
      } else {
        document.getElementById('priceProduct').value = formatRupiahRp(calculated.toString());
      }
    } else {
      var realValue = valuePromo.val();
      document.getElementById('promoValueInput').value = formatPercent(realValue);
      var splitedDiscount = realValue.split('%');
      var splittedPrice = splitedDiscount.join('');
      var splitValueDiscount = splittedPrice.split('.');
      var realDiscount = splitValueDiscount.join('');
      document.getElementById('promoValue').value = realDiscount;
      var discount = realDiscount / 100;
      var totalDeducted = parseInt(priceProduct) * discount;
      // console.log(totalDeducted);
      var calculatePromo = parseInt(priceProduct) - parseInt(totalDeducted);
      document.getElementById('priceProduct').value = formatRupiahRp(calculatePromo.toString());
    }
    
  })
</script>
@endsection --}}