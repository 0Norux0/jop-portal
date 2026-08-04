<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Domain\Identity\Enums\Permission as Perm;
use App\Domain\Portal\Models\Job;
use App\Filament\Resources\JobResource\Pages;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class JobResource extends Resource
{
    protected static ?string $model = Job::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-briefcase';

    protected static string | \UnitEnum | null $navigationGroup = 'Hiring';

    public static function canViewAny(): bool
    {
        return Auth::user()?->can(Perm::EditNonSensitiveSettings->value) ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('title')->required()->maxLength(255)->live(onBlur: true)
                ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug((string) $state))),
            TextInput::make('slug')->required()->maxLength(255)->unique(ignoreRecord: true),
            Select::make('employer_id')->relationship('employer', 'name')->searchable()->preload(),
            Select::make('job_category_id')->relationship('category', 'name')->searchable()->preload()->label('Category'),
            Select::make('status')->options([
                'draft' => 'Draft',
                'published' => 'Published',
                'paused' => 'Paused',
                'closed' => 'Closed',
            ])->required(),
            TextInput::make('country')->maxLength(120),
            TextInput::make('city')->maxLength(120),
            Select::make('work_mode')->options(['on_site' => 'On-site', 'remote' => 'Remote', 'hybrid' => 'Hybrid'])->required(),
            Select::make('employment_type')->options(['full_time' => 'Full-time', 'part_time' => 'Part-time', 'contract' => 'Contract', 'freelance' => 'Freelance', 'internship' => 'Internship'])->required(),
            TextInput::make('currency')->maxLength(8),
            TextInput::make('salary_min')->numeric(),
            TextInput::make('salary_max')->numeric(),
            TextInput::make('vacancies')->numeric()->default(1),
            DatePicker::make('application_deadline'),
            Toggle::make('is_featured'),
            Toggle::make('is_urgent'),
            Toggle::make('visa_sponsorship'),
            Toggle::make('relocation_support'),
            Textarea::make('description')->rows(5)->columnSpanFull(),
            TagsInput::make('responsibilities')->separator(',')->columnSpanFull(),
            TagsInput::make('skills')->separator(',')->columnSpanFull(),
            TagsInput::make('requirements')->separator(',')->columnSpanFull(),
            TagsInput::make('benefits')->separator(',')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('updated_at', 'desc')
            ->columns([
                TextColumn::make('title')->searchable()->sortable(),
                TextColumn::make('employer.name')->searchable()->sortable()->placeholder('No employer'),
                TextColumn::make('category.name')->label('Category')->sortable()->placeholder('Uncategorized'),
                TextColumn::make('country')->searchable()->sortable(),
                TextColumn::make('work_mode')->badge()->sortable(),
                TextColumn::make('status')->badge()->sortable(),
                IconColumn::make('is_featured')->boolean(),
                IconColumn::make('is_urgent')->boolean(),
                TextColumn::make('applications_count')->counts('applications')->label('Applications')->sortable(),
            ])
            ->recordActions([EditAction::make(), DeleteAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJobs::route('/'),
            'create' => Pages\CreateJob::route('/create'),
            'edit' => Pages\EditJob::route('/{record}/edit'),
        ];
    }
}
