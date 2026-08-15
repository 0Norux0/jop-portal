<?php

declare(strict_types=1);

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CheckMaintenanceMode;
use App\Http\Middleware\EnsureAdminSectionAccess;
use App\Http\Middleware\EnsureEmployerAccess;
use App\Http\Middleware\EnsurePortalCapabilityEnabled;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [CheckMaintenanceMode::class]);
        $middleware->alias([
            'admin.section' => EnsureAdminSectionAccess::class,
            'employer' => EnsureEmployerAccess::class,
            'portal.capability' => EnsurePortalCapabilityEnabled::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->respond(function (Response $response, Throwable $exception, Request $request) {
            if ($response->getStatusCode() !== 419) {
                return $response;
            }

            $referer = (string) $request->headers->get('referer', '');
            $loginUrl = str_contains($referer, '/admin') || $request->is('admin/*')
                ? url('/admin/login')
                : url('/login');

            return redirect($loginUrl)
                ->withInput($request->except(['password', 'password_confirmation', '_token']))
                ->withErrors(['session' => 'Your login session expired. Please try signing in again.']);
        });
    })->create();
