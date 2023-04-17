@extends('main.main')
@section('content')
@include('main.topbar')

<div class="row mt-3">
    <div class="col-md-12">
        <div class="card card-custom">
            <div class="row">
                <div class="col-md-6">
                    <p class="title-text pl-2">Detail Role</p>
                </div>
            </div>
            <hr class="hr-custom">
            <div class="row">
                <div class="col-md-12">
                    <form action="{{route('post.edit.role')}}" class="form-custom form stacked" method="POST" id="formEditRole" ajax="true">
                        <input readonly disabled type="hidden" value="{{$role->id}}" name="idRole">
                        <input readonly disabled type="hidden" value="{{$role->title}}" name="titleOld">
                        <div class="row p-0">
                            <div class="col-md-6 mt-2">
                                <div class="form-group">
                                    <label for="">Title</label>
                                    <div class="with-error">
                                        <input readonly disabled type="text" name="titleRole" id="titleRole" class="form-control" placeholder="Masukkan Title Role" value="{{ucwords($role->title)}}">
                                        <img src="{{asset('image/icon-error-form.svg')}}">
                                    </div>
                                </div>
                            </div>
                            @php
                                if($role->status == true){
                                    $style = 'status-active';
                                    $text = 'active';
                                }else{
                                    $style = 'status-remove';
                                    $text = 'Inactive';
                                }
                            @endphp
                            <div class="col-md-5 mt-2 ml-2">
                                <div class="form-group">
                                    <label for="">Status</label>
                                    <div class="row">
                                        <div class="col-md-4 p-0">
                                            <select readonly disabled name="flagStatus" id="flagStatus" class="form-select {{$style}}">
                                                <option value="1" {{$role->status == true ? 'selected' : ''}}>Active</option>
                                                <option value="0" {{$role->status == false ? 'selected' : ''}}>Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row p-0">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Permission</label>
                                </div>
                            </div>
                            <div id="accordion" class="col-md-12">
                                @for($i = 0; $i < count($permission); $i++) 
                                <div class="card">
                                    <div class="card-header" id="heading{{$i}}">
                                        <h5 class="mb-0">
                                            <button type="button" class="btn btn-link" data-toggle="collapse"
                                                data-target="#{{$permission[$i]['title']}}" aria-expanded="true"
                                                aria-controls="{{$permission[$i]['title']}}">
                                                {{ucwords(str_replace('_', ' ', $permission[$i]['title']))}}
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="{{$permission[$i]['title']}}" class="collapse show" aria-labelledby="headingOne"
                                        data-parent="#accordion">
                                        <div class="card-body">
                                            <div class="row">
                                                @for ($j = 0; $j < count($permission[$i]['child']); $j++)    
                                                <div class="col-md-12 p-0 mb-2">
                                                    <label for="permission_{{$permission[$i]['child'][$j]['title']}}" class="checkbox pl-0 mb-2">
                                                        <div class="row">
                                                            <div class="col-md-1 pr-0">
                                                                <center>
                                                                    <input class="fitur-role" name="permission" id="permission_{{$permission[$i]['title']}}" type="checkbox" value="{{$permission[$i]['id']}}" {{in_array($permission[$i]['child'][$j]['id'], $listPermission) ? 'checked' : ''}}>
                                                                    <span class="cbver2"></span>
                                                                </center>
                                                            </div>
                                                            <div class="col-md-6 pl-0">
                                                                <p class="text-fitur"> {{ucwords(str_replace('_', ' ', $permission[$i]['child'][$j]['title']))}}</p>
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                 </div>
                                @endfor
                            </div>
                            {{-- ACCORDION  --}}
                        </div>
                        <hr class="hr-card">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <button type="button" class="btn-back"><a href="{{route('get.role')}}">Kembali</a></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
