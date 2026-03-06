<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class UpdateUserLastSeen
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $userId = Auth::id();
            $cacheKey = 'user_last_seen_' . $userId;

            // Only update DB if 5 minutes have passed since last update
            if (!\Illuminate\Support\Facades\Cache::has($cacheKey)) {
                User::where('id', $userId)->update(['last_seen_at' => now()]);
                \Illuminate\Support\Facades\Cache::put($cacheKey, true, 300); // 5 minutes
            }
        }
        return $next($request);
    }

}
