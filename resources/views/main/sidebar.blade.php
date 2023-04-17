<aside class="main-sidebar mbl custom-width" id="sidebarUtama">
    <section class="section-logo-mobile">
        <a href="{{route('home')}}">
            <img src="{{asset('image/navbar/akomart.svg')}}" class="logo-sidebar">
        </a>
        
    </section>
    
    {{-- <button type="button">
    </button> --}}
    <section class="sidebar">
        
        <ul class="menu-sidebar">
            <div class="logo mbl bg-logo p-0">
                <div class="row">
                    <div class="col-md-12 col-7">
                        <a href="{{route('home')}}" id="logo">
                            <img src="{{asset('image/navbar/akomart.svg')}}" class="logo-sidebar">
                        </a>
                    </div>

                </div>
            </div>
            <!-- user active -->
            <li class="user">
                <div class="name">
                    <div class="row">
                        <div class="col-md-2">
                            <img src="{{asset('image/sidebar/icon-profile.svg')}}" alt="">
                        </div>
                        <div class="col-md-10">
                            <p>{{session('session_id.role_name')}}</p>
                            <p>{{session('session_id.name')}}</p>
                        </div>
                    </div>
                </div>
            </li>
            <!-- list menu -->
            <div class="text-title {{ $grup == 'dashboard' ? 'menu-active' : '' }} mb-2">
                <img src="{{asset('image/sidebar/dashboard.svg')}}" alt="">
                <a href="{{route('home')}}" class="sidetext {{ $grup == 'dashboard' ? 'active' : '' }}">Dashboard</a>
            </div>
            @if(session('session_id.type') == '1')
            @if(in_array('user_management_list', session('session_id.permission')))
            <div class="text-title {{ $grup == 'user' ? 'menu-active' : '' }} mb-2">
                <img src="{{asset('image/sidebar/user.svg')}}" alt="">
                <a href="{{route('get.user')}}" class="sidetext {{ $grup == 'user' ? 'active' : '' }}">Management User</a>
            </div>
            @endif
            @if(in_array('role_management_list', session('session_id.permission')))
            <div class="text-title {{ $grup == 'role' ? 'menu-active' : '' }} mb-2">
                <img src="{{asset('image/sidebar/role.svg')}}" alt="">
                <a href="{{route('get.role')}}" class="sidetext {{ $grup == 'role' ? 'active' : '' }}">Management Role</a>
            </div>
            @endif

            @if(in_array('category_management_list', session('session_id.permission')))
            <div class="text-title {{ $grup == 'category' ? 'menu-active' : '' }} mb-2">
                <img src="{{asset('image/sidebar/category.svg')}}" alt="">
                <a href="{{route('get.category')}}" class="sidetext {{ $grup == 'category' ? 'active' : '' }}">Management
                    Category</a>
            </div>
            @endif
            @if(in_array('banner_management_list', session('session_id.permission')))
            <div class="text-title {{ $grup == 'banner' ? 'menu-active' : '' }} mb-2">
                <img src="{{asset('image/sidebar/banner.svg')}}" alt="">
                <a href="{{route('get.banner')}}" class="sidetext {{ $grup == 'category' ? 'active' : '' }}">Management
                    Banner</a>
            </div>
            @endif
            @if(in_array('product_management_list', session('session_id.permission')))
            <div class="text-title {{ $grup == 'product' ? 'menu-active' : '' }} mb-2">
                <img src="{{asset('image/sidebar/product.svg')}}" alt="">
                <a href="{{route('get.product')}}" class="sidetext {{ $grup == 'product' ? 'active' : '' }}">Management
                    Product</a>
            </div>
            @endif

            @if(in_array('inventory_management_list', session('session_id.permission')))
            <div class="text-title {{ $grup == 'inventory' ? 'menu-active' : '' }} mb-2">
                <img src="{{asset('image/sidebar/inventory.svg')}}" alt="">
                <a href="{{route('get.inventory')}}" class="sidetext {{ $grup == 'inventory' ? 'active' : '' }}">Management
                    Inventory</a>
            </div>
            @endif

            @if(in_array('voucher_management_list', session('session_id.permission')))
            <div class="text-title {{ $grup == 'voucher' ? 'menu-active' : '' }} mb-2">
                <img src="{{asset('image/sidebar/voucher.svg')}}" alt="">
                <a href="{{route('get.voucher')}}" class="sidetext {{ $grup == 'voucher' ? 'active' : '' }}">Management
                    Voucher</a>
            </div>
            @endif

            @if(in_array('customer_management_list', session('session_id.permission')))
            <div class="text-title {{ $grup == 'customer' ? 'menu-active' : '' }} mb-2">
                <img src="{{asset('image/sidebar/customer.svg')}}" alt="">
                <a href="{{route('get.customer')}}" class="sidetext {{ $grup == 'customer' ? 'active' : '' }}">Management
                    Customer</a>
            </div>
            @endif

            @if(in_array('transaction_management_list', session('session_id.permission')))
            <div class="text-title {{ $grup == 'transaction' ? 'menu-active' : '' }} mb-2">
                <img src="{{asset('image/sidebar/transaction.svg')}}" alt="">
                <a href="{{route('get.transaction')}}"
                    class="sidetext {{ $grup == 'transaction' ? 'active' : '' }}">Transaksi</a>
            </div>
            @endif
            @else
            @endif
        </ul>
    </section>
</aside>