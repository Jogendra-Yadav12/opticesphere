<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\AdminStaffController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\BlogPostController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\CurrencyRateController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\NewsletterController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\OrderReturnController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PaymentMethodController;
use App\Http\Controllers\Admin\PayoutController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\RefundController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\SellerController;
use App\Http\Controllers\Admin\SellerRequestController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ShippingMethodController;
use App\Http\Controllers\Admin\ShippingZoneController;
use App\Http\Controllers\Admin\SupportTicketController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\TaxRateController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Seller\AuthController as SellerAuthController;
use App\Http\Controllers\Seller\CategoryController as SellerCategoryController;
use App\Http\Controllers\Seller\CouponController as SellerCouponController;
use App\Http\Controllers\Seller\CustomerController as SellerCustomerController;
use App\Http\Controllers\Seller\PlanController as SellerPlanController;
use App\Http\Controllers\Seller\RequestController as SellerRequestSubmitController;
use App\Http\Controllers\Seller\ReviewController as SellerReviewController;
use App\Http\Controllers\Seller\SettingController as SellerSettingController;
use Illuminate\Support\Facades\Route;

// Storefront
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [HomeController::class, 'shop'])->name('shop');
Route::get('/productDetails/{id}', [HomeController::class, 'productDetails'])->name('customer.product.details');
Route::get('/aboutus', [HomeController::class, 'aboutus'])->name('aboutus');
Route::get('/contact', [HomeController::class, 'contactus'])->name('contactus');
Route::get('/store/{slug}', [HomeController::class, 'store'])->name('store');
Route::get('/page/{slug}', [HomeController::class, 'page'])->name('page');
Route::post('/newsletter/subscribe', [HomeController::class, 'newsletterSubscribe'])->name('newsletter.subscribe');

Route::middleware('auth')->group(function () {
    Route::post('/store/{slug}/review', [HomeController::class, 'storeReview'])->name('store.review');
    Route::post('/product/{id}/review', [HomeController::class, 'productReview'])->name('product.review');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::delete('/cart/remove/{item}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/coupon', [CartController::class, 'applyCoupon'])->name('cart.coupon.apply');
    Route::delete('/cart/coupon', [CartController::class, 'removeCoupon'])->name('cart.coupon.remove');

    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist');
    Route::post('/wishlist/add', [WishlistController::class, 'add'])->name('wishlist.add');
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::delete('/wishlist/remove/{item}', [WishlistController::class, 'remove'])->name('wishlist.remove');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');

    Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/orders', [ProfileController::class, 'orders'])->name('orders');
    Route::get('/orders/{order}', [ProfileController::class, 'orderDetail'])->name('order.detail');
    Route::get('/address', [ProfileController::class, 'address'])->name('address');
    Route::post('/address', [ProfileController::class, 'addressStore'])->name('address.store');
    Route::post('/address/{address}', [ProfileController::class, 'addressUpdate'])->name('address.update');
    Route::put('/address/{address}/default', [ProfileController::class, 'addressMakeDefault'])->name('address.makeDefault');
    Route::delete('/address/{address}', [ProfileController::class, 'addressDestroy'])->name('address.destroy');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::post('/attribute', [AttributeController::class, 'store'])->name('attribute.store');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register');

    Route::get('/password/reset', [PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/password/email', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/password/reset/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [PasswordResetController::class, 'reset'])->name('password.update');

    Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');

    Route::get('/seller/login', [SellerAuthController::class, 'showLogin'])->name('seller.login');
    Route::post('/seller/login', [SellerAuthController::class, 'login'])->name('seller.login.submit');
    Route::get('/seller/register', [SellerAuthController::class, 'showRegister'])->name('seller.register');
    Route::post('/seller/register', [SellerAuthController::class, 'register'])->name('seller.register.submit');
    Route::get('/seller/register/plan', [SellerAuthController::class, 'showPlan'])->name('seller.register.plan');
    Route::post('/seller/register/plan', [SellerAuthController::class, 'selectPlan'])->name('seller.register.plan.submit');
});

Route::middleware('auth')->group(function () {
    Route::get('/password/confirm', [PasswordResetController::class, 'showConfirmForm'])->name('password.confirm');
    Route::post('/password/confirm', [PasswordResetController::class, 'confirm'])->name('password.confirm');

    Route::get('/email/verify', [EmailVerificationController::class, 'notice'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])->name('verification.verify');
    Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend'])->name('verification.resend');
});

// Admin
Route::prefix('admin')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

        Route::get('/category', [CategoryController::class, 'index'])->name('admin.category.index');
        Route::get('/category/create', [CategoryController::class, 'create'])->name('admin.category.create');
        Route::post('/category', [CategoryController::class, 'store'])->name('admin.category.store');
        Route::get('/category/{category}/edit', [CategoryController::class, 'edit'])->name('admin.category.edit');
        Route::put('/category/{category}', [CategoryController::class, 'update'])->name('admin.category.update');
        Route::delete('/category/{category}', [CategoryController::class, 'destroy'])->name('admin.category.destroy');

        Route::get('/product', [ProductController::class, 'index'])->name('admin.product.index');
        Route::get('/product/create', [ProductController::class, 'create'])->name('admin.product.create');
        Route::post('/product', [ProductController::class, 'store'])->name('admin.product.store');
        Route::get('/product/{product}/edit', [ProductController::class, 'edit'])->name('admin.product.edit');
        Route::put('/product/{product}', [ProductController::class, 'update'])->name('admin.product.update');
        Route::delete('/product/{product}', [ProductController::class, 'destroy'])->name('admin.product.destroy');
        Route::put('/product/{product}/approve', [ProductController::class, 'approve'])->name('admin.product.approve');
        Route::put('/product/{product}/reject', [ProductController::class, 'reject'])->name('admin.product.reject');

        Route::get('/order', [OrderController::class, 'index'])->name('admin.order.index');
        Route::get('/order/{order}', [OrderController::class, 'show'])->name('admin.order.show');
        Route::put('/order/{order}/status', [OrderController::class, 'updateStatus'])->name('admin.order.updateStatus');
        Route::delete('/order/{order}', [OrderController::class, 'destroy'])->name('admin.order.destroy');

        Route::get('/customer', [CustomerController::class, 'index'])->name('admin.customer.index');
        Route::get('/customer/{customer}/edit', [CustomerController::class, 'edit'])->name('admin.customer.edit');
        Route::put('/customer/{customer}', [CustomerController::class, 'update'])->name('admin.customer.update');
        Route::delete('/customer/{customer}', [CustomerController::class, 'destroy'])->name('admin.customer.destroy');

        Route::get('/seller', [SellerController::class, 'index'])->name('admin.seller.index');
        Route::get('/seller/{seller}/edit', [SellerController::class, 'edit'])->name('admin.seller.edit');
        Route::put('/seller/{seller}', [SellerController::class, 'update'])->name('admin.seller.update');
        Route::put('/seller/{seller}/approve', [SellerController::class, 'approve'])->name('admin.seller.approve');
        Route::put('/seller/{seller}/reject', [SellerController::class, 'reject'])->name('admin.seller.reject');
        Route::delete('/seller/{seller}', [SellerController::class, 'destroy'])->name('admin.seller.destroy');

        Route::get('/seller-requests', [SellerRequestController::class, 'index'])->name('admin.seller.requests');
        Route::put('/seller-requests/{request}/approve', [SellerRequestController::class, 'approve'])->name('admin.seller.requests.approve');
        Route::put('/seller-requests/{request}/reject', [SellerRequestController::class, 'reject'])->name('admin.seller.requests.reject');

        Route::get('/plan', [PlanController::class, 'index'])->name('admin.plan.index');
        Route::post('/plan', [PlanController::class, 'store'])->name('admin.plan.store');
        Route::get('/plan/{plan}/edit', [PlanController::class, 'edit'])->name('admin.plan.edit');
        Route::put('/plan/{plan}', [PlanController::class, 'update'])->name('admin.plan.update');
        Route::delete('/plan/{plan}', [PlanController::class, 'destroy'])->name('admin.plan.destroy');

        Route::get('/payment', [PaymentMethodController::class, 'index'])->name('admin.payment.index');
        Route::get('/payment/create', [PaymentMethodController::class, 'create'])->name('admin.payment.create');
        Route::post('/payment', [PaymentMethodController::class, 'store'])->name('admin.payment.store');
        Route::get('/payment/{payment}/edit', [PaymentMethodController::class, 'edit'])->name('admin.payment.edit');
        Route::put('/payment/{payment}', [PaymentMethodController::class, 'update'])->name('admin.payment.update');
        Route::delete('/payment/{payment}', [PaymentMethodController::class, 'destroy'])->name('admin.payment.destroy');

        Route::get('/shipping', [ShippingMethodController::class, 'index'])->name('admin.shipping.index');
        Route::get('/shipping/create', [ShippingMethodController::class, 'create'])->name('admin.shipping.create');
        Route::post('/shipping', [ShippingMethodController::class, 'store'])->name('admin.shipping.store');
        Route::get('/shipping/{shipping}/edit', [ShippingMethodController::class, 'edit'])->name('admin.shipping.edit');
        Route::put('/shipping/{shipping}', [ShippingMethodController::class, 'update'])->name('admin.shipping.update');
        Route::delete('/shipping/{shipping}', [ShippingMethodController::class, 'destroy'])->name('admin.shipping.destroy');

        Route::get('/coupon', [CouponController::class, 'index'])->name('admin.coupon.index');
        Route::get('/coupon/create', [CouponController::class, 'create'])->name('admin.coupon.create');
        Route::post('/coupon', [CouponController::class, 'store'])->name('admin.coupon.store');
        Route::get('/coupon/{coupon}/edit', [CouponController::class, 'edit'])->name('admin.coupon.edit');
        Route::put('/coupon/{coupon}', [CouponController::class, 'update'])->name('admin.coupon.update');
        Route::delete('/coupon/{coupon}', [CouponController::class, 'destroy'])->name('admin.coupon.destroy');

        Route::get('/banner', [BannerController::class, 'index'])->name('admin.banner.index');
        Route::get('/banner/create', [BannerController::class, 'create'])->name('admin.banner.create');
        Route::post('/banner', [BannerController::class, 'store'])->name('admin.banner.store');
        Route::get('/banner/{banner}/edit', [BannerController::class, 'edit'])->name('admin.banner.edit');
        Route::put('/banner/{banner}', [BannerController::class, 'update'])->name('admin.banner.update');
        Route::delete('/banner/{banner}', [BannerController::class, 'destroy'])->name('admin.banner.destroy');

        Route::get('/setting', [SettingController::class, 'index'])->name('admin.setting.index');
        Route::post('/setting', [SettingController::class, 'store'])->name('admin.setting.store');

        Route::get('/brand', [BrandController::class, 'index'])->name('admin.brand.index');
        Route::get('/brand/create', [BrandController::class, 'create'])->name('admin.brand.create');
        Route::post('/brand', [BrandController::class, 'store'])->name('admin.brand.store');
        Route::get('/brand/{brand}/edit', [BrandController::class, 'edit'])->name('admin.brand.edit');
        Route::put('/brand/{brand}', [BrandController::class, 'update'])->name('admin.brand.update');
        Route::delete('/brand/{brand}', [BrandController::class, 'destroy'])->name('admin.brand.destroy');

        Route::get('/tag', [TagController::class, 'index'])->name('admin.tag.index');
        Route::get('/tag/create', [TagController::class, 'create'])->name('admin.tag.create');
        Route::post('/tag', [TagController::class, 'store'])->name('admin.tag.store');
        Route::get('/tag/{tag}/edit', [TagController::class, 'edit'])->name('admin.tag.edit');
        Route::put('/tag/{tag}', [TagController::class, 'update'])->name('admin.tag.update');
        Route::delete('/tag/{tag}', [TagController::class, 'destroy'])->name('admin.tag.destroy');

        Route::get('/review', [ReviewController::class, 'index'])->name('admin.review.index');
        Route::get('/review/{review}/edit', [ReviewController::class, 'edit'])->name('admin.review.edit');
        Route::put('/review/{review}', [ReviewController::class, 'update'])->name('admin.review.update');
        Route::put('/review/{review}/approve', [ReviewController::class, 'approve'])->name('admin.review.approve');
        Route::put('/review/{review}/reject', [ReviewController::class, 'reject'])->name('admin.review.reject');
        Route::delete('/review/{review}', [ReviewController::class, 'destroy'])->name('admin.review.destroy');

        Route::get('/ticket', [SupportTicketController::class, 'index'])->name('admin.ticket.index');
        Route::get('/ticket/{ticket}', [SupportTicketController::class, 'show'])->name('admin.ticket.show');
        Route::post('/ticket/{ticket}/reply', [SupportTicketController::class, 'reply'])->name('admin.ticket.reply');
        Route::put('/ticket/{ticket}/status', [SupportTicketController::class, 'updateStatus'])->name('admin.ticket.updateStatus');

        Route::get('/refund', [RefundController::class, 'index'])->name('admin.refund.index');
        Route::put('/refund/{refund}/approve', [RefundController::class, 'approve'])->name('admin.refund.approve');
        Route::put('/refund/{refund}/reject', [RefundController::class, 'reject'])->name('admin.refund.reject');

        Route::get('/return', [OrderReturnController::class, 'index'])->name('admin.return.index');
        Route::put('/return/{orderReturn}/approve', [OrderReturnController::class, 'approve'])->name('admin.return.approve');
        Route::put('/return/{orderReturn}/reject', [OrderReturnController::class, 'reject'])->name('admin.return.reject');

        Route::get('/payout', [PayoutController::class, 'index'])->name('admin.payout.index');
        Route::put('/payout/{payout}/approve', [PayoutController::class, 'approve'])->name('admin.payout.approve');
        Route::put('/payout/{payout}/process', [PayoutController::class, 'process'])->name('admin.payout.process');
        Route::put('/payout/{payout}/reject', [PayoutController::class, 'reject'])->name('admin.payout.reject');

        Route::get('/announcement', [AnnouncementController::class, 'index'])->name('admin.announcement.index');
        Route::get('/announcement/create', [AnnouncementController::class, 'create'])->name('admin.announcement.create');
        Route::post('/announcement', [AnnouncementController::class, 'store'])->name('admin.announcement.store');
        Route::get('/announcement/{announcement}/edit', [AnnouncementController::class, 'edit'])->name('admin.announcement.edit');
        Route::put('/announcement/{announcement}', [AnnouncementController::class, 'update'])->name('admin.announcement.update');
        Route::delete('/announcement/{announcement}', [AnnouncementController::class, 'destroy'])->name('admin.announcement.destroy');

        Route::get('/page', [PageController::class, 'index'])->name('admin.page.index');
        Route::get('/page/create', [PageController::class, 'create'])->name('admin.page.create');
        Route::post('/page', [PageController::class, 'store'])->name('admin.page.store');
        Route::get('/page/{page}/edit', [PageController::class, 'edit'])->name('admin.page.edit');
        Route::put('/page/{page}', [PageController::class, 'update'])->name('admin.page.update');
        Route::delete('/page/{page}', [PageController::class, 'destroy'])->name('admin.page.destroy');

        Route::get('/blog', [BlogPostController::class, 'index'])->name('admin.blog.index');
        Route::get('/blog/create', [BlogPostController::class, 'create'])->name('admin.blog.create');
        Route::post('/blog', [BlogPostController::class, 'store'])->name('admin.blog.store');
        Route::get('/blog/{blog}/edit', [BlogPostController::class, 'edit'])->name('admin.blog.edit');
        Route::put('/blog/{blog}', [BlogPostController::class, 'update'])->name('admin.blog.update');
        Route::delete('/blog/{blog}', [BlogPostController::class, 'destroy'])->name('admin.blog.destroy');

        Route::get('/admin-staff', [AdminStaffController::class, 'index'])->name('admin.staff.index');
        Route::get('/admin-staff/create', [AdminStaffController::class, 'create'])->name('admin.staff.create');
        Route::post('/admin-staff', [AdminStaffController::class, 'store'])->name('admin.staff.store');
        Route::get('/admin-staff/{staff}/edit', [AdminStaffController::class, 'edit'])->name('admin.staff.edit');
        Route::put('/admin-staff/{staff}', [AdminStaffController::class, 'update'])->name('admin.staff.update');
        Route::delete('/admin-staff/{staff}', [AdminStaffController::class, 'destroy'])->name('admin.staff.destroy');

        Route::get('/tax', [TaxRateController::class, 'index'])->name('admin.tax.index');
        Route::get('/tax/create', [TaxRateController::class, 'create'])->name('admin.tax.create');
        Route::post('/tax', [TaxRateController::class, 'store'])->name('admin.tax.store');
        Route::get('/tax/{tax}/edit', [TaxRateController::class, 'edit'])->name('admin.tax.edit');
        Route::put('/tax/{tax}', [TaxRateController::class, 'update'])->name('admin.tax.update');
        Route::delete('/tax/{tax}', [TaxRateController::class, 'destroy'])->name('admin.tax.destroy');

        Route::get('/shipping-zone', [ShippingZoneController::class, 'index'])->name('admin.shippingZone.index');
        Route::get('/shipping-zone/create', [ShippingZoneController::class, 'create'])->name('admin.shippingZone.create');
        Route::post('/shipping-zone', [ShippingZoneController::class, 'store'])->name('admin.shippingZone.store');
        Route::get('/shipping-zone/{shippingZone}/edit', [ShippingZoneController::class, 'edit'])->name('admin.shippingZone.edit');
        Route::put('/shipping-zone/{shippingZone}', [ShippingZoneController::class, 'update'])->name('admin.shippingZone.update');
        Route::delete('/shipping-zone/{shippingZone}', [ShippingZoneController::class, 'destroy'])->name('admin.shippingZone.destroy');

        Route::get('/newsletter', [NewsletterController::class, 'index'])->name('admin.newsletter.index');
        Route::delete('/newsletter/{newsletter}', [NewsletterController::class, 'destroy'])->name('admin.newsletter.destroy');

        Route::get('/currency', [CurrencyRateController::class, 'index'])->name('admin.currency.index');
        Route::get('/currency/create', [CurrencyRateController::class, 'create'])->name('admin.currency.create');
        Route::post('/currency', [CurrencyRateController::class, 'store'])->name('admin.currency.store');
        Route::get('/currency/{currencyRate}/edit', [CurrencyRateController::class, 'edit'])->name('admin.currency.edit');
        Route::put('/currency/{currencyRate}', [CurrencyRateController::class, 'update'])->name('admin.currency.update');
        Route::delete('/currency/{currencyRate}', [CurrencyRateController::class, 'destroy'])->name('admin.currency.destroy');
    });

// Seller
Route::prefix('seller')
    ->middleware(['auth', 'role:seller', 'plan'])
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('seller.dashboard');

        Route::get('/product', [ProductController::class, 'index'])->name('seller.product.index');
        Route::get('/product/create', [ProductController::class, 'create'])->name('seller.product.create');
        Route::post('/product', [ProductController::class, 'store'])->name('seller.product.store');
        Route::get('/product/{product}/edit', [ProductController::class, 'edit'])->name('seller.product.edit');
        Route::put('/product/{product}', [ProductController::class, 'update'])->name('seller.product.update');
        Route::delete('/product/{product}', [ProductController::class, 'destroy'])->name('seller.product.destroy');

        Route::get('/order', [OrderController::class, 'index'])->name('seller.order.index');

        Route::get('/plan', [SellerPlanController::class, 'index'])->name('seller.plan.index');
        Route::post('/plan', [SellerPlanController::class, 'update'])->name('seller.plan.update');
        Route::get('/order/{order}', [OrderController::class, 'show'])->name('seller.order.show');

        Route::get('/customer', [SellerCustomerController::class, 'index'])->name('seller.customer.index');

        Route::get('/settings', [SellerSettingController::class, 'index'])->name('seller.settings');
        Route::post('/settings', [SellerSettingController::class, 'update'])->name('seller.settings.update');

        Route::get('/reviews', [SellerReviewController::class, 'index'])->name('seller.reviews');
        Route::post('/reviews/{review}/reply', [SellerReviewController::class, 'reply'])->name('seller.reviews.reply');

        Route::get('/request/category', [SellerRequestSubmitController::class, 'requestCategory'])->name('seller.request.category');
        Route::post('/request/category', [SellerRequestSubmitController::class, 'storeCategory'])->name('seller.request.category.store');
        Route::get('/request/coupon', [SellerRequestSubmitController::class, 'requestCoupon'])->name('seller.request.coupon');
        Route::post('/request/coupon', [SellerRequestSubmitController::class, 'storeCoupon'])->name('seller.request.coupon.store');

        Route::get('/coupon', [SellerCouponController::class, 'index'])->name('seller.coupon.index');

        Route::get('/category', [SellerCategoryController::class, 'index'])->name('seller.category.index');
    });
