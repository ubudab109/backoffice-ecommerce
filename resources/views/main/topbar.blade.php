<div class="row mt-3">
    <div class="col-md-6">
        <p class="title-page">@foreach($breadcumb as $bread) <a href="{{$bread['url']}}">{{$bread['title']}}</a> {{$bread['url'] == '/' ? '' : '/'}} @endforeach {{isset($subtitle) ? $subtitle : ''}}</p>
    </div>
    <div class="col-md-6">
        <div class="logout right">
            <a href="{{route('get.logout')}}">
                <img src="{{asset('image/icon-logout.svg')}}" alt="">
                Logout
            </a>
        </div>
    </div>
</div>