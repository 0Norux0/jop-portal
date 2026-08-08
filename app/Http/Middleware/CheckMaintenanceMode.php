<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Support\MaintenanceMode;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $settings = MaintenanceMode::settings();

        if (! $settings['enabled'] || $this->allowed($request, $settings['allowed_paths'])) {
            return $next($request);
        }

        return response()
            ->view('maintenance', ['message' => $settings['message']], 503)
            ->header('Retry-After', '900');
    }

    /**
     * @param  array<int, string>  $patterns
     */
    private function allowed(Request $request, array $patterns): bool
    {
        if ($request->user()?->can('platform.enter_maintenance_mode')) {
            return true;
        }

        foreach ($patterns as $pattern) {
            if ($request->is($pattern)) {
                return true;
            }
        }

        return false;
    }
}
