@extends('layouts.admin')

@section('content')

@include('admin.nav')

            <!-- PAGE INNER
            ================================================== -->
            <div class="page-inner">

                <!-- PAGE MAIN WRAPPER
                ================================================== -->
                <div id="main-wrapper">

                    <!-- PAGE HEADER
                    ================================================== -->
                    <div class="row grid-margin">
                        <div class="col-12">
                            <div class="card card-white">
                                <div class="card-body d-flex flex-wrap justify-content-between align-items-center">
                                    <div>
                                        <h4 class="mb-1">Dashboard</h4>
                                        <p class="text-muted mb-0">Welcome back, {{ Auth::user()->name }} &middot; {{ today()->format('l, d F Y') }}</p>
                                    </div>
                                    <div class="text-muted">
                                        <i class="fas fa-calendar-alt me-1"></i>{{ $isAdmin ? 'Admin overview' : 'Seller overview' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end page header -->

                    <!-- MAIN STAT CARDS
                    ================================================== -->
                    <div class="row">
                        <div class="col-xl-3 col-md-6 half-gutter grid-margin">
                            <div class="card card-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <div>
                                            <p class="text-muted mb-1">Total Orders</p>
                                            <h4 class="mb-0 text-blue">{{ $orderCount }}</h4>
                                        </div>
                                        <div>
                                            <i class="fas fa-shopping-cart text-blue fs-2"></i>
                                        </div>
                                    </div>
                                    <div class="custom-progress bg-blue progress mb-0">
                                        <div class="animated custom-bar progress-bar slideInLeft" style="width:70%" role="progressbar"></div>
                                    </div>
                                    @if(isset($pendingOrders) && $pendingOrders > 0)
                                        <small class="text-muted">{{ $pendingOrders }} pending</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 half-gutter grid-margin">
                            <div class="card card-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <div>
                                            <p class="text-muted mb-1">Total Revenue</p>
                                            <h4 class="mb-0 text-orange">₹{{ number_format($revenue, 2) }}</h4>
                                        </div>
                                        <div>
                                            <i class="fas fa-chart-pie text-orange fs-2"></i>
                                        </div>
                                    </div>
                                    <div class="custom-progress bg-orange progress mb-0">
                                        <div class="animated custom-bar progress-bar slideInLeft" style="width:80%" role="progressbar"></div>
                                    </div>
                                    @if(isset($todayRevenue))
                                        <small class="text-muted">₹{{ number_format($todayRevenue, 2) }} today</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 half-gutter grid-margin">
                            <div class="card card-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <div>
                                            <p class="text-muted mb-1">Customers</p>
                                            <h4 class="mb-0 text-green">{{ $customerCount }}</h4>
                                        </div>
                                        <div>
                                            <i class="far fa-user text-green fs-2"></i>
                                        </div>
                                    </div>
                                    <div class="custom-progress bg-green progress mb-0">
                                        <div class="animated custom-bar progress-bar slideInLeft" style="width:60%" role="progressbar"></div>
                                    </div>
                                    <small class="text-muted">registered customers</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 half-gutter grid-margin">
                            <div class="card card-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <div>
                                            <p class="text-muted mb-1">Products</p>
                                            <h4 class="mb-0 text-pink">{{ $productCount }}</h4>
                                        </div>
                                        <div>
                                            <i class="fas fa-box text-pink fs-2"></i>
                                        </div>
                                    </div>
                                    <div class="custom-progress bg-pink progress mb-0">
                                        <div class="animated custom-bar progress-bar slideInLeft" style="width:90%" role="progressbar"></div>
                                    </div>
                                    @if(isset($pendingApprovals))
                                        <small class="text-muted">{{ $pendingApprovals }} awaiting approval</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end main stat cards -->

                    @if($isAdmin)
                    <!-- MINI STAT STRIP (admin)
                    ================================================== -->
                    <div class="row grid-margin">
                        <div class="col-xl-2 col-md-4 col-6 half-gutter">
                            <div class="card card-white">
                                <div class="card-body text-center py-3">
                                    <h4 class="mb-1 text-blue">{{ $todayOrders }}</h4>
                                    <small class="text-muted">Today's Orders</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-md-4 col-6 half-gutter">
                            <div class="card card-white">
                                <div class="card-body text-center py-3">
                                    <h4 class="mb-1 text-orange">₹{{ number_format($todayRevenue) }}</h4>
                                    <small class="text-muted">Today's Revenue</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-md-4 col-6 half-gutter">
                            <div class="card card-white">
                                <div class="card-body text-center py-3">
                                    <h4 class="mb-1 text-danger">{{ $pendingOrders }}</h4>
                                    <small class="text-muted">Pending Orders</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-md-4 col-6 half-gutter">
                            <div class="card card-white">
                                <div class="card-body text-center py-3">
                                    <h4 class="mb-1 text-pink">{{ $pendingApprovals }}</h4>
                                    <small class="text-muted">Pending Approvals</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-md-4 col-6 half-gutter">
                            <div class="card card-white">
                                <div class="card-body text-center py-3">
                                    <h4 class="mb-1 text-green">{{ $totalSellers }}</h4>
                                    <small class="text-muted">Sellers</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-md-4 col-6 half-gutter">
                            <div class="card card-white">
                                <div class="card-body text-center py-3">
                                    <h4 class="mb-1 text-orange">{{ $pendingSellers }}</h4>
                                    <small class="text-muted">Pending Sellers</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end mini stat strip -->
                    @endif

                    <!-- CHARTS
                    ================================================== -->
                    <div class="row grid-margin">
                        <div class="col-xl-8 half-gutter">
                            <div class="card card-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            <h5 class="mb-0">Sales Overview</h5>
                                            <small class="text-muted">Last 14 days</small>
                                        </div>
                                    </div>
                                    <canvas id="salesChart" height="280"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 half-gutter">
                            <div class="card card-white">
                                <div class="card-body">
                                    <h5 class="mb-3">Orders by Status</h5>
                                    @if($statusBreakdown->isEmpty())
                                        <p class="text-muted mb-0">No orders yet.</p>
                                    @else
                                        <canvas id="statusChart" height="280"></canvas>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end charts -->

                    <!-- RECENT ORDERS + TOP PRODUCTS
                    ================================================== -->
                    <div class="row grid-margin">
                        <div class="col-xl-7 half-gutter">
                            <div class="card card-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="mb-0">Recent Orders</h5>
                                        <a href="{{ route($isAdmin ? 'admin.order.index' : 'seller.order.index') }}" class="btn btn-sm btn-outline-primary">View all</a>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th scope="col">Order</th>
                                                    <th scope="col">Customer</th>
                                                    <th scope="col">Date</th>
                                                    <th scope="col">Total</th>
                                                    <th scope="col">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($recentOrders as $order)
                                                    @php $badge = [
                                                        'pending' => 'bg-warning text-dark',
                                                        'processing' => 'bg-info text-white',
                                                        'shipped' => 'bg-primary text-white',
                                                        'delivered' => 'bg-success text-white',
                                                        'cancelled' => 'bg-secondary text-white',
                                                        'refunded' => 'bg-danger text-white',
                                                    ][$order->status] ?? 'bg-light text-dark'; @endphp
                                                    <tr>
                                                        <td><a href="{{ route($isAdmin ? 'admin.order.show' : 'seller.order.show', $order) }}">#{{ $order->order_number }}</a></td>
                                                        <td>{{ $order->user?->name ?? '—' }}</td>
                                                        <td>{{ $order->placed_at ? $order->placed_at->format('d M Y, H:i') : '—' }}</td>
                                                        <td>₹{{ number_format($order->total_amount, 2) }}</td>
                                                        <td><span class="badge {{ $badge }}">{{ ucfirst($order->status) }}</span></td>
                                                    </tr>
                                                @empty
                                                    <tr><td colspan="5" class="text-center text-muted">No orders yet.</td></tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-5 half-gutter">
                            <div class="card card-white">
                                <div class="card-body">
                                    <h5 class="mb-3">Top Selling Products</h5>
                                    @forelse($topProducts as $i => $product)
                                        <div class="d-flex align-items-center justify-content-between py-2 border-bottom">
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-light text-dark me-3">{{ $i + 1 }}</span>
                                                <span class="text-truncate" style="max-width:220px">{{ $product->product_name }}</span>
                                            </div>
                                            <div class="text-end">
                                                <strong>₹{{ number_format((float) $product->total_revenue, 2) }}</strong>
                                                <small class="d-block text-muted">{{ $product->total_qty }} sold</small>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-muted mb-0">No sales yet.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end recent orders + top products -->

                    <!-- QUICK LINKS
                    ================================================== -->
                    <div class="row">
                        <div class="col-12 half-gutter">
                            <div class="card card-white">
                                <div class="card-body">
                                    <h5 class="mb-3">Quick Actions</h5>
                                    <div class="row g-3">
                                        @foreach($quickLinks as $link)
                                            <div class="col-6 col-md-3 col-xl-2">
                                                <a href="{{ route($link['route']) }}" class="text-decoration-none d-block quick-link" style="transition:all .15s ease">
                                                    <div class="border rounded text-center p-3">
                                                        <i class="fas {{ $link['icon'] }} {{ $link['color'] }} fs-3 mb-2 d-block"></i>
                                                        <span class="text-body">{{ $link['label'] }}</span>
                                                    </div>
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end quick links -->

                </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/chart.umd.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var salesEl = document.getElementById('salesChart');
            if (salesEl) {
                new Chart(salesEl, {
                    type: 'line',
                    data: {
                        labels: @json($salesTrend['labels']),
                        datasets: [{
                            label: 'Revenue (₹)',
                            data: @json($salesTrend['revenue']),
                            borderColor: '#5b7cfa',
                            backgroundColor: 'rgba(91, 124, 250, 0.12)',
                            fill: true,
                            tension: 0.35,
                            pointBackgroundColor: '#5b7cfa',
                            pointRadius: 3,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: { display: true, position: 'top' }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { callback: function (value) { return '₹' + value; } }
                            }
                        }
                    }
                });
            }

            var statusEl = document.getElementById('statusChart');
            if (statusEl) {
                var labels = @json($statusBreakdown->pluck('status')->map(fn ($s) => ucfirst($s))->values()->all());
                var data = @json($statusBreakdown->pluck('total')->values()->all());
                var colors = {
                    pending: '#f39c12', processing: '#3498db', shipped: '#5b7cfa',
                    delivered: '#27ae60', cancelled: '#95a5a6', refunded: '#9b59b6'
                };
                var bg = @json($statusBreakdown->pluck('status')->map(fn ($s) => $colors[$s] ?? '#95a5a6')->values()->all());

                new Chart(statusEl, {
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: data,
                            backgroundColor: bg,
                            borderWidth: 2,
                            borderColor: '#ffffff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        cutout: '62%',
                        plugins: {
                            legend: { position: 'bottom' }
                        }
                    }
                });
            }
        });
    </script>
@endsection
