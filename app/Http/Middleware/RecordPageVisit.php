<?php

namespace App\Http\Middleware;

use App\Models\PageVisit;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RecordPageVisit
{
    protected array $exclude = [
        'login', 'register', 'logout',
        'password', 'sanctum',
        'admin', 'pimpinan', 'api',
        '_debugbar', '_ignition',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethod('GET') && !$this->shouldExclude($request)) {
            try {
                PageVisit::create([
                    'url'        => $request->path(),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'visited_at' => now(),
                ]);
            } catch (\Throwable $e) {
                logger()->warning('RecordPageVisit error: ' . $e->getMessage());
            }
        }

        return $next($request);
    }

    protected function shouldExclude(Request $request): bool
    {
        $path = $request->path();
        foreach ($this->exclude as $pattern) {
            if (str_starts_with($path, $pattern)) return true;
        }
        return false;
    }
}