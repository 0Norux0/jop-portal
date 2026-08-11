<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Domain\Identity\Enums\Permission as Perm;
use App\Domain\Portal\Models\EmployerServiceRequest;
use App\Filament\Resources\EmployerServiceRequestResource\Pages;
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

class EmployerServiceRequestResource extends Resource
{
    protected static ?string $model = EmployerServiceRequest::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-banknotes';

    protected static string | \UnitEnum | null $navigationGroup = 'Hiring';

    protected static ?string $navigationLabel = 'Paid Service Requests';

    public static function canViewAny(): bool
    {
        return Auth::user()?->can(Perm::EditNonSensitiveSettings->value) ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Select::make('employer_id')->relationship('employer', 'name')->searchable()->preload()->required(),
            Select::make('job_id')->relationship('job', 'title')->searchable()->preload(),
            Select::make('candidate_id')->relationship('candidate', 'full_name')->searchable()->preload(),
            Select::make('type')->options([
                'subscription' => 'Subscription',
                'advertising' => 'Advertising',
                'candidate_contact' => 'Candidate contact',
                'recruitment_package' => 'Recruitment package',
                'credit_topup' => 'Credit top-up',
                'matching_support' => 'Candidate matching support',
                'premium_matching' => 'Premium candidate matching',
                'ai_recruitment' => 'AI recruitment tools',
            ])->required(),
            TextInput::make('title')->required()->maxLength(160),
            Select::make('status')->options([
                'requested' => 'Requested',
                'reviewing' => 'Reviewing',
                'approved' => 'Approved',
                'active' => 'Active',
                'completed' => 'Completed',
                'declined' => 'Declined',
            ])->required(),
            TextInput::make('budget')->numeric(),
            DateTimePicker::make('reviewed_at'),
            Textarea::make('notes')->rows(4)->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('title')->searchable()->sortable(),
                TextColumn::make('employer.name')->searchable()->sortable(),
                TextColumn::make('type')->badge()->sortable(),
                TextColumn::make('status')->badge()->sortable(),
                TextColumn::make('budget')->money('USD')->sortable(),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->recordActions([EditAction::make(), DeleteAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployerServiceRequests::route('/'),
            'create' => Pages\CreateEmployerServiceRequest::route('/create'),
            'edit' => Pages\EditEmployerServiceRequest::route('/{record}/edit'),
        ];
    }
}
