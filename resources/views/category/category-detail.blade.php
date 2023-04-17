@extends('main.main')
@section('content')
@include('main.topbar')

<div class="row mt-3">
    <div class="col-md-12">
        <div class="card card-custom">
            <div class="row">
                <div class="col-md-6">
                    <p class="title-text pl-2">Edit Category</p>
                </div>
            </div>
            <hr class="hr-custom">
            <div class="row">
                <div class="col-md-12">
                    <form enctype="multipart/form-data" action="{{route('post.edit.category')}}" class="form-custom form stacked" method="POST"
                        id="formEditCategory" ajax="true" files="true">
                        <input type="hidden" value="{{$category->id}}" name="idCategory">

                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-2 col-form-label">Kode Kategori</label>
                            <div class="col-sm-5">
                                <input type="text" readonly name="codeCategory" id="codeCategory"
                                            class="form-control file-upload" value="{{$category->code_category}}"
                                            placeholder="Masukkan Kode Kategori">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-2 col-form-label">Nama Kategori</label>
                            <div class="col-sm-5">
                                <input type="text" value="{{$category->name}}" name="categoryName"
                                            id="categoryName" class="form-control" placeholder="Masukkan Nama Kategory"
                                            value="{{ucwords($category->name)}}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-2 col-form-label">Icon</label>
                            <div class="col-sm-5">
                                <img src="{{$category->icon}}" alt="" width="100">
                                <div class="custom-file d-flex flex-row-reverse">
                                    <input type="file" class="custom-file-input" name="file" id="file_upload" dir="rtl">
                                    <label class="custom-file-label text-right" for="customFile">choose files</label>
                                </div>
                                <p class="text-muted">*Ukuran Gambar : 70 x 70 px</p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-2 col-form-label">Status</label>
                            @php
                            if($category->status == true){
                                $active = 'checked';
                                $text = 'active';
                            } else {
                                $active = '';
                                $text = 'inactive';
                            }
                            @endphp
                            <div class='custom-control custom-switch ml-3'>
                                <input type='checkbox' name="flagStatus" {{$active}} class='custom-control-input' id='flagStatus'>
                                <label class='custom-control-label' for='flagStatus'></label>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <button type="button" id="btnResetEditCategory" class="btn btn-reset ml-2 mr-4">Reset</button>
                            <button type="button" id="btnEditCategory" class="btn btn-save">Simpan</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('modal')
<div class="modal fade" id="modalEditCategory" aria-labelledby="modalEditCategoryLabel" aria-hidden="true">
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
                            <form enctype="multipart/form-data" action="{{route('post.edit.category')}}" class="form-custom form stacked" method="POST"
                                id="formEditCategory" ajax="true">
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