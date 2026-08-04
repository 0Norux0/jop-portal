<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Domain\Identity\Enums\AccountStatus;
use App\Domain\Identity\Enums\Permission as Perm;
use App\Domain\Identity\Enums\Role as RoleEnum;
use App\Domain\Identity\Models\User;
use App\Filament\Resources\UserResource\Pages;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-users';

    protected static string | \UnitEnum | null $navigationGroup = 'Users';

    public static function canViewAny(): bool
    {
        return Auth::user()?->can('viewAny', User::class) ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        /** @var User $actor */
        $actor = Auth::user();

        // Roles selectable through the UI never include super administrator
        // unless the actor is themselves a super administrator.
        $assignableRoles = collect(RoleEnum::cases())
            ->reject(fn (RoleEnum $r) => $r === RoleEnum::SuperAdministrator && ! $actor->isSuperAdministrator())
            ->mapWithKeys(fn (RoleEnum $r) => [$r->value => $r->label()])
            ->all();

        return $schema->schema([
            TextInput::make('name')->required()->maxLength(255),
            TextInput::make('email')->email()->required()->maxLength(255)
                ->unique(ignoreRecord: true),
            Select::make('status')
                ->options(collect(AccountStatus::cases())
                    ->mapWithKeys(fn (AccountStatus $s) => [$s->value => $s->label()])->all())
                ->required(),
            Select::make('roles')
                ->relationship('roles', 'name')
                ->multiple()
                ->preload()
                ->options($assignableRoles)
                ->visible(fn () => $actor->can(Perm::AssignRoles->value)),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('public_id')->label('Public ID')->limit(12)->toggleable(),
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('email')->searchable()->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (AccountStatus $state) => $state->label()),
                TextColumn::make('roles.name')->badge()->label('Roles'),
                TextColumn::make('last_login_at')->dateTime()->sortable()->placeholder('Never'),
                TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Action::make('suspend')
                    ->icon('heroicon-o-no-symbol')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (User $record) => Auth::user()?->can('suspend', $record)
                        && $record->status !== AccountStatus::Suspended)
                    ->action(fn (User $record) => app(\App\Domain\Identity\Actions\ChangeUserStatus::class)
                        ->change($record, AccountStatus::Suspended)),
                Action::make('reactivate')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (User $record) => Auth::user()?->can('suspend', $record)
                        && $record->status === AccountStatus::Suspended)
                    ->action(fn (User $record) => app(\App\Domain\Identity\Actions\ChangeUserStatus::class)
                        ->change($record, AccountStatus::Active)),
            ]);
    }

    /** @return array<string, \Filament\Resources\Pages\PageRegistration> */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
