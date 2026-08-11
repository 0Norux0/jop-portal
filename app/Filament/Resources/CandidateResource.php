<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Domain\Identity\Enums\Permission as Perm;
use App\Domain\Portal\Models\Candidate;
use App\Filament\Resources\CandidateResource\Pages;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class CandidateResource extends Resource
{
    protected static ?string $model = Candidate::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-identification';

    protected static string | \UnitEnum | null $navigationGroup = 'Talent';

    public static function canViewAny(): bool
    {
        return Auth::user()?->can(Perm::EditNonSensitiveSettings->value) ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('full_name')->required()->maxLength(255),
            TextInput::make('headline')->maxLength(255),
            TextInput::make('email')->email()->maxLength(160),
            TextInput::make('phone')->maxLength(80),
            TextInput::make('country')->maxLength(120),
            TextInput::make('city')->maxLength(120),
            TextInput::make('current_job_title')->maxLength(160),
            TextInput::make('preferred_job_category')->maxLength(160),
            TagsInput::make('preferred_locations')->separator(',')->columnSpanFull(),
            Select::make('employment_type_preference')->options([
                'full_time' => 'Full-time',
                'part_time' => 'Part-time',
                'contract' => 'Contract',
                'freelance' => 'Freelance',
                'internship' => 'Internship',
            ]),
            Select::make('work_mode_preference')->options([
                'on_site' => 'On-site',
                'hybrid' => 'Hybrid',
                'remote' => 'Remote',
                'flexible' => 'Flexible',
            ]),
            TextInput::make('work_authorization')->maxLength(160),
            TextInput::make('visa_requirements')->maxLength(160),
            TextInput::make('relocation_preference')->maxLength(160),
            TextInput::make('linkedin_url')->url()->maxLength(255),
            TextInput::make('portfolio_url')->url()->maxLength(255),
            TextInput::make('cv_path')->maxLength(255),
            Select::make('verification_status')->options([
                'unverified' => 'Unverified',
                'pending' => 'Pending',
                'verified' => 'Verified',
                'rejected' => 'Rejected',
            ])->required(),
            Select::make('availability_status')->options([
                'open_to_work' => 'Open to work',
                'not_looking' => 'Not looking',
                'hired' => 'Hired',
            ])->required(),
            TextInput::make('trust_score')->numeric()->minValue(0)->maxValue(100)->default(0),
            TagsInput::make('skills')->separator(',')->columnSpanFull(),
            TagsInput::make('languages')->separator(',')->columnSpanFull(),
            Textarea::make('bio')->rows(4)->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('full_name')->searchable()->sortable(),
                TextColumn::make('headline')->searchable()->limit(32),
                TextColumn::make('email')->searchable(),
                TextColumn::make('country')->searchable()->sortable(),
                TextColumn::make('verification_status')->badge()->sortable(),
                TextColumn::make('availability_status')->badge()->sortable(),
                TextColumn::make('trust_score')->sortable()->suffix('/100'),
                TextColumn::make('applications_count')->counts('applications')->label('Applications')->sortable(),
            ])
            ->recordActions([EditAction::make(), DeleteAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCandidates::route('/'),
            'create' => Pages\CreateCandidate::route('/create'),
            'edit' => Pages\EditCandidate::route('/{record}/edit'),
        ];
    }
}
