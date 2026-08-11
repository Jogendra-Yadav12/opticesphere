@php
    $active = $active ?? '';
    $user = auth()->user();
@endphp

<div class="account-pannel">

    <div class="p-4">
        <div class="text-center">
            <div class="pb-3">
                <img class="img-fluid rounded-circle img-thumbnail" src="{{ asset('img/avatar/t-3.jpg') }}" alt="...">
            </div>
            <h6 class="mb-0 display-28">{{ $user->name }}</h6>
            <small>Joined {{ $user->created_at->format('F d, Y') }}</small>
        </div>
    </div>

    <div class="list-group">
        <a class="list-group-items {{ $active === 'profile' ? 'active' : '' }}" href="{{ route('profile') }}"><i class="ti-user pe-2"></i>Profile</a>
        <a class="list-group-items {{ $active === 'orders' ? 'active' : '' }}" href="{{ route('orders') }}"><i class="ti-bag pe-2"></i>Orders<span class="badge badge-pill">{{ $user->orders()->count() }}</span></a>
        <a class="list-group-items {{ $active === 'address' ? 'active' : '' }}" href="{{ route('address') }}"><i class="ti-location-pin pe-2"></i>Addresses</a>
        <a class="list-group-items {{ $active === 'wishlist' ? 'active' : '' }}" href="{{ route('wishlist') }}"><i class="ti-heart pe-2"></i>Wishlist<span class="badge badge-pill">{{ $user->wishlists()->count() }}</span></a>
        <a class="list-group-items" href="#" onclick="event.preventDefault(); document.getElementById('account-logout-form').submit();"><i class="ti-power-off pe-2"></i>Logout</a>
        <form id="account-logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
    </div>

</div>
