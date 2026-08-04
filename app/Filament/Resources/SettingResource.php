<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Domain\Identity\Enums\Permission as Perm;
use App\Domain\Shared\Models\Setting;
use App\Filament\Resources\SettingResource\Pages;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string | \UnitEnum | null $navigationGroup = 'System';

    protected static ?string $navigationLabel = 'Settings';

    public static function canViewAny(): bool
    {
        return Auth::user()?->can(Perm::ViewSystemSettings->value) ?? false;
    }

    public static function canCreate(): bool
    {
        return Auth::user()?->can(Perm::EditNonSensitiveSettings->value) ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('group')->required()->maxLength(120),
            TextInput::make('key')->required()->maxLength(180)->unique(ignoreRecord: true),
            Select::make('type')->options([
                'string' => 'String',
                'boolean' => 'Boolean',
                'integer' => 'Integer',
                'json' => 'JSON',
            ])->required(),
            Toggle::make('is_sensitive'),
            Textarea::make('value')->rows(8)->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('group')->badge()->searchable()->sortable(),
                TextColumn::make('key')->searchable()->sortable(),
                TextColumn::make('type')->badge()->sortable(),
                IconColumn::make('is_sensitive')->boolean(),
                TextColumn::make('updated_at')->dateTime()->sortable(),
            ])
            ->recordActions([EditAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSettings::route('/'),
            'create' => Pages\CreateSetting::route('/create'),
            'edit' => Pages\EditSetting::route('/{record}/edit'),
        ];
    }
}
