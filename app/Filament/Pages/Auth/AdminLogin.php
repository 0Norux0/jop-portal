<?php

declare(strict_types=1);

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login;
use Illuminate\Contracts\Support\Htmlable;

class AdminLogin extends Login
{
    protected static string $layout = 'filament-panels::components.layout.base';

    protected string $view = 'filament.pages.auth.admin-login';

    public function getHeading(): string | Htmlable
    {
        return 'Admin sign in';
    }

    public function getSubheading(): string | Htmlable | null
    {
        return 'Access the protected control center for users, jobs, content, verification, and platform settings.';
    }
}
