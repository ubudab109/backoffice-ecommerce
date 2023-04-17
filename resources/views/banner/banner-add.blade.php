@extends('main.main')
@section('content')
@include('main.topbar')

<div class="row mt-3">
  <div class="col-md-12">
    <div class="card card-custom">
      <div class="row">
        <div class="col-md-6">
          <p class="title-text pl-2">Add Banner</p>
        </div>
      </div>
      <hr class="hr-custom">
      <div class="row">
        <div class="col-md-12">
          <form enctype="multipart/form-data" action="{{route('post.add.banner')}}" class="form-custom form stacked"
            method="POST" id="formAddBanner" ajax="true" files="true">

            <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Judul*</label>
                <div class="col-sm-5">
                  <input type="text" name="titleBanner" id="titleBanner" class="form-control file-upload"
                  placeholder="Masukkan Judul Banner">
                </div>
            </div>

            <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Gambar*</label>
                <div class="col-sm-5">
                  <div class="custom-file d-flex flex-row-reverse">
                      <input type="file" class="custom-file-input" name="file" id="file_upload" dir="rtl">
                      <label class="custom-file-label text-right" for="customFile"></label>
                  </div>
                  <p class="text-muted">*Ukuran Gambar : 704 x 250 px</p>
              </div>
            </div>

            <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Status</label>
                <div class="custom-control custom-switch ml-3">
                    <input type="checkbox" name="flagStatus" id="flagStatus" checked class="custom-control-input">
                    <label class="custom-control-label" for="flagStatus"></label>
                </div>
            </div>

            <div class="row mb-3">
                <button type="button" id="btnResetAddBanner" class="btn btn-reset ml-2 mr-4">Reset</button>
                <button type="button" id="btnAddBanner" class="btn btn-save">Simpan</button>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('modal')
<div class="modal fade" id="modalAddBanner" aria-labelledby="modalAddBannerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-custom">
    <div class="modal-content content-footer-status">
      <div class="modal-body">
        <center>
          <p class="title-notif" id="titleNotif">Kamu akan menambahkan Banner ?</p>
        </center>
        <center>
          <div class="row">
            <div class="col-md-12" id="btnNotif">
              <form enctype="multipart/form-data" action="{{route('post.add.banner')}}" class="form-custom form stacked"
                method="POST" id="formAddBanner" ajax="true">

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
@section("script")
    <script>
        $(".custom-file input").change(function (e) {
            var files = [];
            for (var i = 0; i < $(this)[0].files.length; i++) {
                files.push($(this)[0].files[i].name);
            }
            $(this).next(".custom-file-label").html(files.join(", "));
        });
    </script>
@endsection