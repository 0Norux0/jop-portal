<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Domain\Identity\Enums\Permission as Perm;
use App\Domain\Portal\Models\TrustReport;
use App\Filament\Resources\TrustReportResource\Pages;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class TrustReportResource extends Resource
{
    protected static ?string $model = TrustReport::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-exclamation-triangle';

    protected static string | \UnitEnum | null $navigationGroup = 'Trust & Safety';

    protected static ?string $navigationLabel = 'Reports';

    public static function canViewAny(): bool
    {
        return Auth::user()?->can(Perm::ViewReports->value) ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Select::make('reporter_id')->relationship('reporter', 'email')->searchable()->preload(),
            Select::make('subject_type')->options([
                'job' => 'Job',
                'employer' => 'Employer',
                'candidate' => 'Candidate',
                'user' => 'User',
                'other' => 'Other',
            ])->required(),
            TextInput::make('subject_reference')->maxLength(255),
            TextInput::make('reason')->required()->maxLength(255),
            Select::make('priority')->options(['low' => 'Low', 'normal' => 'Normal', 'high' => 'High', 'urgent' => 'Urgent'])->required(),
            Select::make('status')->options(['open' => 'Open', 'reviewing' => 'Reviewing', 'resolved' => 'Resolved', 'dismissed' => 'Dismissed'])->required(),
            DateTimePicker::make('resolved_at'),
            Textarea::make('description')->rows(4)->columnSpanFull(),
            Textarea::make('resolution_notes')->rows(4)->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('subject_type')->badge()->sortable(),
                TextColumn::make('subject_reference')->searchable()->limit(28),
                TextColumn::make('reason')->searchable()->limit(36),
                TextColumn::make('priority')->badge()->sortable(),
                TextColumn::make('status')->badge()->sortable(),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->recordActions([EditAction::make(), DeleteAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTrustReports::route('/'),
            'create' => Pages\CreateTrustReport::route('/create'),
            'edit' => Pages\EditTrustReport::route('/{record}/edit'),
        ];
    }
}
