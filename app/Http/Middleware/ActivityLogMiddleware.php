<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ActivityLog;

class ActivityLogMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Lanjutkan request dulu
        $response = $next($request);

        // Jangan log file statis (gambar/css/js) biar database gak penuh
        if (!$request->is('images/*') && !$request->is('build/*') && !$request->is('css/*') && !$request->is('js/*') && !$request->is('favicon.ico')) {
            ActivityLog::create([
                'user_id' => $request->user() ? $request->user()->id : null,
                'action' => 'ACCESS',
                'description' => 'Mengunjungi: ' . ($request->path() == '/' ? 'Home' : $request->path()),
                'ip_address' => $request->ip(),
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'status_code' => $response->getStatusCode(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        return $response;
    }
}