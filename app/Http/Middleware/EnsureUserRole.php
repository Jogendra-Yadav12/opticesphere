<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        if (! in_array($request->user()->role, $roles, true)) {
            abort(403, 'You do not have permission to access this area.');
        }

        if ($request->user()->role === 'seller'
            && in_array($request->user()->status, ['pending', 'rejected', 'banned', 'suspended'], true)) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('seller.login')->withErrors(['email' => 'Your seller account is not approved. Please contact support.']);
        }

        return $next($request);
    }
}
