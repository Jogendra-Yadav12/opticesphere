<?php
use App\Models\Subscription;
use App\Models\User;
use App\Models\Vendor;

$temp = User::create([
    'name' => 'Temp Plan Test',
    'email' => 'tempplan'.uniqid().'@test.com',
    'password' => bcrypt('secret'),
    'role' => 'seller',
    'status' => 'approved',
]);
$vendor = Vendor::create([
    'user_id' => $temp->id,
    'store_name' => 'Temp Plan Test Store',
    'slug' => 'temp-plan-test-'.uniqid(),
    'status' => 'approved',
]);

$controller = new App\Http\Controllers\Admin\SellerController();
$ref = new ReflectionMethod($controller, 'updateSellerPlan');

$ref->invoke($controller, $temp, 6);
$sub = Subscription::where('user_id', $temp->id)->latest('id')->first();
print('plan 6: subs='.Subscription::where('user_id', $temp->id)->count().' latest='.($sub?->status).' plan='.($sub?->planTier?->plan?->name).PHP_EOL);

$ref->invoke($controller, $temp, 6);
print('same plan again: subs='.Subscription::where('user_id', $temp->id)->count().' (should stay 1)'.PHP_EOL);

$ref->invoke($controller, $temp, 7);
$sub2 = Subscription::where('user_id', $temp->id)->latest('id')->first();
print('change to 7: subs='.Subscription::where('user_id', $temp->id)->count().' latest='.($sub2?->status).' plan='.($sub2?->planTier?->plan?->name).' cancelled='.Subscription::where('user_id', $temp->id)->where('status', 'cancelled')->count().PHP_EOL);

$ref->invoke($controller, $temp, 7);
$sub3 = Subscription::where('user_id', $temp->id)->latest('id')->first();
print('resubmit 7: subs='.Subscription::where('user_id', $temp->id)->count().' (should stay 2) latest='.($sub3?->status).PHP_EOL);

Subscription::where('user_id', $temp->id)->delete();
$vendor->delete();
$temp->delete();
print('cleaned up'.PHP_EOL);
