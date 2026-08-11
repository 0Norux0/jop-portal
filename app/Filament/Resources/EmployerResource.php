<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Domain\Identity\Enums\Permission as Perm;
use App\Domain\Portal\Models\Employer;
use App\Filament\Resources\EmployerResource\Pages;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
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
            TextInput::make('company_size')->maxLength(120),
            TextInput::make('country')->maxLength(120),
            TextInput::make('city')->maxLength(120),
            TextInput::make('website_url')->url()->maxLength(255),
            FileUpload::make('logo_path')->image()->directory('company-assets')->disk('public')->label('Logo'),
            FileUpload::make('cover_path')->image()->directory('company-assets')->disk('public')->label('Cover image'),
            TextInput::make('contact_name')->maxLength(160),
            TextInput::make('contact_email')->email()->maxLength(160),
            TextInput::make('contact_phone')->maxLength(80),
            TextInput::make('billing_email')->email()->maxLength(160),
            Select::make('billing_plan')->options([
                'free' => 'Free',
                'growth' => 'Growth',
                'premium' => 'Premium',
                'enterprise' => 'Enterprise',
            ])->required(),
            Select::make('premium_status')->options([
                'not_upgraded' => 'Not upgraded',
                'requested' => 'Requested',
                'trial' => 'Trial',
                'active' => 'Active',
                'expired' => 'Expired',
            ])->required(),
            TextInput::make('job_post_limit')->numeric()->default(2),
            TextInput::make('featured_job_credits')->numeric()->default(0),
            TextInput::make('candidate_search_credits')->numeric()->default(10),
            TextInput::make('cv_access_credits')->numeric()->default(1),
            TextInput::make('candidate_contact_credits')->numeric()->default(1),
            TextInput::make('matching_request_credits')->numeric()->default(0),
            TextInput::make('ai_recruitment_credits')->numeric()->default(0),
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
            Toggle::make('is_published')->label('Public company page'),
            Toggle::make('advertising_enabled'),
            Toggle::make('learning_enabled'),
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
                TextColumn::make('billing_plan')->badge()->sortable(),
                TextColumn::make('premium_status')->badge()->sortable()->toggleable(),
                TextColumn::make('cv_access_credits')->label('CV credits')->sortable()->toggleable(),
                TextColumn::make('candidate_contact_credits')->label('Contact credits')->sortable()->toggleable(),
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
