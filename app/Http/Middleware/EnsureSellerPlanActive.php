<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSellerPlanActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->role === 'seller') {
            $routeName = $request->route()?->getName();

            if (! in_array($routeName, ['seller.plan.index', 'seller.plan.update'], true)) {
                $subscription = $user->subscriptions()->latest('id')->first();

                $valid = $subscription
                    && in_array($subscription->status, ['active', 'trialing'], true)
                    && $subscription->current_period_end
                    && $subscription->current_period_end->isFuture();

                if (! $valid) {
                    return redirect()
                        ->route('seller.plan.index')
                        ->with('plan_expired', true);
                }
            }
        }

        return $next($request);
    }
}
