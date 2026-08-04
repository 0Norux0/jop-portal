<?php

declare(strict_types=1);

namespace App\Domain\Identity\Enums;

enum RegistrationSource: string
{
    case Web = 'web';
    case AdminCreated = 'admin_created';
    case Invitation = 'invitation';
    case Import = 'import';
    case Api = 'api';

    public function label(): string
    {
        return match ($this) {
            self::Web => 'Web registration',
            self::AdminCreated => 'Created by administrator',
            self::Invitation => 'Organisation invitation',
            self::Import => 'Imported',
            self::Api => 'API',
        };
    }
}
