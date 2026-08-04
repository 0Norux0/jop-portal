<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Domain\Identity\Enums\Permission as Perm;
use App\Domain\Portal\Models\MediaAsset;
use App\Filament\Resources\MediaAssetResource\Pages;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class MediaAssetResource extends Resource
{
    protected static ?string $model = MediaAsset::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-photo';

    protected static string | \UnitEnum | null $navigationGroup = 'Website';

    protected static ?string $navigationLabel = 'Media Library';

    public static function canViewAny(): bool
    {
        return Auth::user()?->can(Perm::EditNonSensitiveSettings->value) ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('name')->required()->maxLength(180),
            Select::make('collection')->options([
                'general' => 'General',
                'branding' => 'Branding',
                'homepage' => 'Homepage',
                'employers' => 'Employers',
                'candidates' => 'Candidates',
                'documents' => 'Documents',
            ])->required(),
            FileUpload::make('path')
                ->label('File')
                ->disk('public')
                ->directory('media-library')
                ->visibility('public')
                ->maxSize(8192)
                ->openable()
                ->downloadable()
                ->required(),
            TextInput::make('mime_type')->maxLength(120),
            TextInput::make('size')->numeric(),
            TextInput::make('alt_text')->maxLength(255),
            Toggle::make('is_public')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('updated_at', 'desc')
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('collection')->badge()->sortable(),
                TextColumn::make('path')->searchable()->limit(44),
                TextColumn::make('mime_type')->toggleable(),
                TextColumn::make('size')->numeric()->toggleable(),
                IconColumn::make('is_public')->boolean(),
                TextColumn::make('updated_at')->dateTime()->sortable(),
            ])
            ->recordActions([EditAction::make(), DeleteAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMediaAssets::route('/'),
            'create' => Pages\CreateMediaAsset::route('/create'),
            'edit' => Pages\EditMediaAsset::route('/{record}/edit'),
        ];
    }
}
