<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;

class CacheResponse
{
    public function handle($request, Closure $next)
    {
        if ($request->isMethod('get') && !auth()->check()) {
            $key = 'page_' . md5($request->fullUrl());

            if (Cache::has($key)) {
                return response(Cache::get($key));
            }

            $response = $next($request);

            if ($response->isSuccessful()) {
                Cache::put($key, $response->getContent(), now()->addMinutes(5));
            }

            return $response;
        }

        return $next($request);
    }
}
