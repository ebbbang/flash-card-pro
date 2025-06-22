<?php

namespace App\Http\Middleware;

use App\Models\ApiLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogApiUsageMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }

    public function terminate(Request $request, Response $response): void
    {
        if ($user = $request->user()) {
            ApiLog::create([
                'user_id' => $user->id,
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'ip' => $request->ip(),
            ]);
        }
    }
}
