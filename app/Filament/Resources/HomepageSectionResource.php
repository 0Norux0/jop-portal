<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Domain\Identity\Enums\Permission as Perm;
use App\Domain\Portal\Models\HomepageSection;
use App\Filament\Resources\HomepageSectionResource\Pages;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class HomepageSectionResource extends Resource
{
    protected static ?string $model = HomepageSection::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-squares-2x2';

    protected static string | \UnitEnum | null $navigationGroup = 'Website';

    protected static ?string $navigationLabel = 'Homepage Sections';

    public static function canViewAny(): bool
    {
        return Auth::user()?->can(Perm::EditNonSensitiveSettings->value) ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('key')->required()->maxLength(120)->unique(ignoreRecord: true),
            TextInput::make('title')->required()->maxLength(180),
            TextInput::make('eyebrow')->maxLength(120),
            TextInput::make('sort_order')->numeric()->default(0),
            Toggle::make('is_enabled')->default(true),
            Textarea::make('description')->rows(4)->columnSpanFull(),
            KeyValue::make('content')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('sort_order')->sortable(),
                TextColumn::make('key')->searchable()->sortable(),
                TextColumn::make('title')->searchable()->sortable(),
                TextColumn::make('eyebrow')->searchable()->toggleable(),
                IconColumn::make('is_enabled')->boolean(),
                TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(),
            ])
            ->recordActions([EditAction::make(), DeleteAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHomepageSections::route('/'),
            'create' => Pages\CreateHomepageSection::route('/create'),
            'edit' => Pages\EditHomepageSection::route('/{record}/edit'),
        ];
    }
}
