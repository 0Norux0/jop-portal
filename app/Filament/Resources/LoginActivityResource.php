<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Domain\Identity\Enums\Permission as Perm;
use App\Domain\Identity\Models\LoginActivity;
use App\Filament\Resources\LoginActivityResource\Pages;
use Filament\Resources\Resource;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class LoginActivityResource extends Resource
{
    protected static ?string $model = LoginActivity::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-shield-check';

    protected static string | \UnitEnum | null $navigationGroup = 'Users';

    protected static ?string $navigationLabel = 'Login Activity';

    public static function canViewAny(): bool
    {
        return Auth::user()?->can(Perm::ViewLoginActivity->value) ?? false;
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('user.email')->label('User')->searchable()->placeholder('Unknown'),
                IconColumn::make('successful')->boolean(),
                TextColumn::make('failure_reason')->placeholder('—'),
                TextColumn::make('ip_address')->label('IP'),
                TextColumn::make('user_agent')->limit(40)->toggleable(),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ]);
    }

    /** @return array<string, \Filament\Resources\Pages\PageRegistration> */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLoginActivities::route('/'),
        ];
    }
}
