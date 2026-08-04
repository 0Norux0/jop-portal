<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Support\PortalCapabilities;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePortalCapabilityEnabled
{
    public function handle(Request $request, Closure $next, string $capability): Response
    {
        abort_unless(PortalCapabilities::enabled($capability), 404);

        return $next($request);
    }
}
