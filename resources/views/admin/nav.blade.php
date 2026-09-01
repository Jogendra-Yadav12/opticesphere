 <!-- PAGE CONTAINER
    ================================================== -->
    <div class="page-container">

        <!-- PAGE SIDEBAR
        ================================================== -->
        <div class="page-sidebar">
            <a class="logo-box" href="{{ url('/') }}">
                @php $adminLogo = \App\Models\Setting::where('key', 'store_logo')->value('value'); @endphp
                @if($adminLogo)
                    <img src="{{ Storage::url('images/logos/'.$adminLogo) }}" alt="..." style="max-height: 40px;">
                @else
                    <span style="font-size: 18px; font-weight: 700;">Admin Panel</span>
                @endif
                <i class="icon-radio_button_unchecked" id="fixed-sidebar-toggle-button"></i>
                <i class="icon-close" id="sidebar-toggle-button-close"></i>
            </a>
            <div class="page-sidebar-inner">
                <div class="page-sidebar-menu">
                    @php
                        $routePrefix = (auth()->check() && auth()->user()->role === 'admin') ? 'admin.' : 'seller.';
                    @endphp
                    <ul class="accordion-menu">
                        <li>
                            <a href="{{ route($routePrefix . 'dashboard') }}"> <i class="menu-icon icon-home4"></i><span>Dashboard</span> </a>
                        </li>
                        @if(auth()->check() && auth()->user()->role === 'admin')
                        <li>
                            <a href="{{ route('admin.category.index') }}"> <i class="menu-icon icon-inbox"></i><span>Category</span> </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.brand.index') }}"> <i class="menu-icon icon-trophy"></i><span>Brands</span> </a>
                        </li>
                        @endif
                        <li>
                            <a href="{{ route($routePrefix . 'product.index') }}"> <i class="menu-icon icon-layers"></i><span>Products</span> </a>
                        </li>
                        <li>
                            <a href="{{ route($routePrefix . 'order.index') }}"> <i class="menu-icon icon-code"></i><span>Orders</span> </a>
                        </li>

                        @if(auth()->check() && auth()->user()->role === 'admin')
                        <li>
                            <a href="{{ route('admin.customer.index') }}"> <i class="menu-icon icon-users"></i><span>Customers</span> </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.seller.index') }}"> <i class="menu-icon icon-briefcase"></i><span>Sellers</span> </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.seller.requests') }}"> <i class="menu-icon icon-envelop"></i><span>Seller Requests</span> </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.coupon.index') }}"> <i class="menu-icon icon-ticket"></i><span>Coupons</span> </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.banner.index') }}"> <i class="menu-icon icon-images"></i><span>Banners</span> </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.review.index') }}"> <i class="menu-icon icon-rate_review"></i><span>Reviews</span> </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.return.index') }}"> <i class="menu-icon icon-restore"></i><span>Returns</span> </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.refund.index') }}"> <i class="menu-icon icon-coin-dollar"></i><span>Refunds</span> </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.payout.index') }}"> <i class="menu-icon icon-payment"></i><span>Payouts</span> </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.payment.index') }}"> <i class="menu-icon icon-credit-card"></i><span>Payment Methods</span> </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.shipping.index') }}"> <i class="menu-icon icon-truck"></i><span>Shipping Methods</span> </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.shippingZone.index') }}"> <i class="menu-icon icon-earth"></i><span>Shipping Zones</span> </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.tax.index') }}"> <i class="menu-icon icon-calculator"></i><span>Tax Rates</span> </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.plan.index') }}"> <i class="menu-icon icon-star"></i><span>Subscription Plans</span> </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.tag.index') }}"> <i class="menu-icon icon-price-tags"></i><span>Tags</span> </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.announcement.index') }}"> <i class="menu-icon icon-announcement"></i><span>Announcements</span> </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.page.index') }}"> <i class="menu-icon icon-pages"></i><span>Pages</span> </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.blog.index') }}"> <i class="menu-icon icon-blog"></i><span>Blog</span> </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.newsletter.index') }}"> <i class="menu-icon icon-newspaper"></i><span>Newsletter</span> </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.ticket.index') }}"> <i class="menu-icon icon-lifebuoy"></i><span>Support Tickets</span> </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.staff.index') }}"> <i class="menu-icon icon-user-tie"></i><span>Staff</span> </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.currency.index') }}"> <i class="menu-icon icon-local_atm"></i><span>Currency Rates</span> </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.setting.index') }}"> <i class="menu-icon icon-settings"></i><span>Global Settings</span> </a>
                        </li>
                        @endif

                        @if(auth()->check() && auth()->user()->role === 'seller')
                        <li>
                            <a href="{{ route('seller.plan.index') }}"> <i class="menu-icon icon-star"></i><span>My Plan</span> </a>
                        </li>
                        <li>
                            <a href="{{ route('seller.customer.index') }}"> <i class="menu-icon icon-users"></i><span>My Customers</span> </a>
                        </li>
                        <li>
                            <a href="{{ route('seller.request.category') }}"> <i class="menu-icon icon-plus"></i><span>Request Category</span> </a>
                        </li>
                        <li>
                            <a href="{{ route('seller.category.index') }}"> <i class="menu-icon icon-layers"></i><span>My Categories</span> </a>
                        </li>
                        <li>
                            <a href="{{ route('seller.request.coupon') }}"> <i class="menu-icon icon-plus"></i><span>Request Coupon</span> </a>
                        </li>
                        <li>
                            <a href="{{ route('seller.coupon.index') }}"> <i class="menu-icon icon-ticket"></i><span>My Coupons</span> </a>
                        </li>
                        <li>
                            <a href="{{ route('seller.reviews') }}"> <i class="menu-icon icon-rate_review"></i><span>Reviews</span> </a>
                        </li>
                        <li>
                            <a href="{{ route('seller.settings') }}"> <i class="menu-icon icon-settings"></i><span>My Settings</span> </a>
                        </li>
                        @endif

                        <li class="menu-divider"></li>
                        <li>
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"> 
                                <i class="menu-icon icon-public"></i><span>Logout</span> 
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

         <!-- PAGE CONTENT
        ================================================== -->
        <div class="page-content">

            <!-- PAGE HEADER
            ================================================== -->
            <div class="page-header">
                <nav class="navbar navbar-default">
                    <div class="container-fluid">
                        <!-- Brand and toggle get grouped for better mobile display -->
                        <div class="navbar-header">
                            <div class="logo-sm">
                                <a href="#" id="sidebar-toggle-button"><i class="fa fa-bars"></i></a>
                                <a class="logo-box" href="{{ route($routePrefix . 'dashboard') }}"><img src="{{ asset('img/logos/logo.png') }}" class="logo" alt="..."></a>
                            </div>
                            <button type="button" class="navbar-toggle collapsed" data-bs-toggle="collapse" data-bs-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                                <i class="fa fa-angle-down"></i>
                            </button>
                        </div>

                        <!-- Collect the nav links, forms, and other content for toggling -->

                        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                            <ul class="nav navbar-nav">
                                <li><a href="#" id="collapsed-sidebar-toggle-button"><i class="fa fa-bars"></i></a></li>
                                <li><a href="#" id="toggle-fullscreen"><i class="fa fa-expand"></i></a></li>
                            </ul>
                            <ul class="nav navbar-nav navbar-right">
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-bell"></i></a>
                                    <ul class="dropdown-menu dropdown-lg dropdown-content">
                                        <li class="drop-title">Notifications<a href="#" class="drop-title-link"><i class="fa fa-angle-right"></i></a></li>
                                        <li class="slimscroll dropdown-notifications">
                                            <ul class="list-unstyled dropdown-oc">
                                                <li>
                                                    <div class="text-center p-3">
                                                        <span class="text-muted">No new notifications.</span>
                                                    </div>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <li class="dropdown user-dropdown">
                                    <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                        @if(auth()->check() && auth()->user()->avatar)
                                            <img src="{{ asset('uploads/avatars/'.auth()->user()->avatar) }}" alt="..." class="rounded-circle" style="width:34px;height:34px;object-fit:cover;">
                                        @else
                                            <span class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width:34px;height:34px;background:#e2e8f0;color:#64748b;font-weight:700;font-size:1rem;">{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}</span>
                                        @endif
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a href="#">{{ auth()->check() ? auth()->user()->name : 'Profile' }}</a></li>
                                        <li><a href="{{ auth()->check() && auth()->user()->role === 'seller' ? route('seller.settings') : route('admin.setting.index') }}">Account Settings</a></li>
                                        <li role="separator" class="divider"></li>
                                        <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Log Out</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <!-- /.navbar-collapse -->
                    </div>
                    <!-- /.container-fluid -->
                </nav>
            </div>

            <!-- Global Alerts Container -->
            <div class="container-fluid mt-3" style="max-width: 1200px; margin: 0 auto; z-index: 9999; position: relative;">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success!</strong> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Whoops! Please fix the following errors:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            </div>