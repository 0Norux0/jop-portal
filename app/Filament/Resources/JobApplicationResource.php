<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Domain\Identity\Enums\Permission as Perm;
use App\Domain\Portal\Models\JobApplication;
use App\Filament\Resources\JobApplicationResource\Pages;
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

class JobApplicationResource extends Resource
{
    protected static ?string $model = JobApplication::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-inbox-stack';

    protected static string | \UnitEnum | null $navigationGroup = 'Hiring';

    protected static ?string $navigationLabel = 'Applications';

    public static function canViewAny(): bool
    {
        return Auth::user()?->can(Perm::EditNonSensitiveSettings->value) ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Select::make('job_id')->relationship('job', 'title')->searchable()->preload()->required(),
            Select::make('candidate_id')->relationship('candidate', 'full_name')->searchable()->preload(),
            Select::make('user_id')->relationship('user', 'email')->searchable()->preload(),
            TextInput::make('candidate_name')->required()->maxLength(255),
            TextInput::make('candidate_email')->email()->required()->maxLength(160),
            Select::make('method')->options([
                'portal_profile' => 'Portal profile',
                'cv' => 'CV',
                'video_profile' => 'Video profile',
                'portfolio' => 'Portfolio',
                'cover_letter' => 'Cover letter',
                'linkedin_profile' => 'LinkedIn profile',
            ])->required(),
            Select::make('status')->options([
                'submitted' => 'Submitted',
                'reviewed' => 'Reviewed',
                'shortlisted' => 'Shortlisted',
                'interview' => 'Interview',
                'rejected' => 'Rejected',
                'hired' => 'Hired',
            ])->required(),
            TextInput::make('linkedin_url')->url()->maxLength(255),
            TextInput::make('cv_path')->maxLength(255),
            DateTimePicker::make('reviewed_at'),
            Textarea::make('cover_letter')->rows(4)->columnSpanFull(),
            Textarea::make('admin_notes')->rows(4)->columnSpanFull(),
            Textarea::make('internal_notes')->rows(4)->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('candidate_name')->searchable()->sortable(),
                TextColumn::make('candidate_email')->searchable(),
                TextColumn::make('job.title')->searchable()->sortable(),
                TextColumn::make('method')->badge()->sortable(),
                TextColumn::make('status')->badge()->sortable(),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->recordActions([EditAction::make(), DeleteAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJobApplications::route('/'),
            'create' => Pages\CreateJobApplication::route('/create'),
            'edit' => Pages\EditJobApplication::route('/{record}/edit'),
        ];
    }
}
