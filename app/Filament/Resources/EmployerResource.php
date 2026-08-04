<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Domain\Identity\Enums\Permission as Perm;
use App\Domain\Portal\Models\Employer;
use App\Filament\Resources\EmployerResource\Pages;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class EmployerResource extends Resource
{
    protected static ?string $model = Employer::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-building-office-2';

    protected static string | \UnitEnum | null $navigationGroup = 'Hiring';

    public static function canViewAny(): bool
    {
        return Auth::user()?->can(Perm::EditNonSensitiveSettings->value) ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('name')->required()->maxLength(255)->live(onBlur: true)
                ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug((string) $state))),
            TextInput::make('slug')->required()->maxLength(255)->unique(ignoreRecord: true),
            TextInput::make('industry')->maxLength(120),
            TextInput::make('country')->maxLength(120),
            TextInput::make('city')->maxLength(120),
            TextInput::make('website_url')->url()->maxLength(255),
            TextInput::make('contact_name')->maxLength(160),
            TextInput::make('contact_email')->email()->maxLength(160),
            TextInput::make('contact_phone')->maxLength(80),
            Select::make('verification_status')->options([
                'pending' => 'Pending',
                'verified' => 'Verified',
                'rejected' => 'Rejected',
            ])->required(),
            Select::make('status')->options([
                'active' => 'Active',
                'suspended' => 'Suspended',
                'archived' => 'Archived',
            ])->required(),
            Textarea::make('description')->rows(4)->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('industry')->searchable()->sortable(),
                TextColumn::make('country')->searchable()->sortable(),
                TextColumn::make('verification_status')->badge()->sortable(),
                TextColumn::make('status')->badge()->sortable(),
                TextColumn::make('jobs_count')->counts('jobs')->label('Jobs')->sortable(),
                TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(),
            ])
            ->recordActions([EditAction::make(), DeleteAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployers::route('/'),
            'create' => Pages\CreateEmployer::route('/create'),
            'edit' => Pages\EditEmployer::route('/{record}/edit'),
        ];
    }
}
