<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Domain\Identity\Enums\Permission;
use App\Support\PortalData;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class PortalContentManager extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';

    protected static string | \UnitEnum | null $navigationGroup = 'Website';

    protected static ?string $navigationLabel = 'Portal Content (Public Data)';

    protected static ?string $title = 'Portal Content (Public Data)';

    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.pages.portal-content-manager';

    /**
     * @var array<string, mixed> | null
     */
    public ?array $data = [];

    public static function canAccess(): bool
    {
        return Auth::user()?->can(Permission::EditNonSensitiveSettings->value) ?? false;
    }

    public function mount(): void
    {
        $this->form->fill(PortalData::toFormState(PortalData::load()));
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Section::make('Reference Data')
                    ->description('Shared countries, categories, badges, languages, currencies, and audience lists.')
                    ->columns(2)
                    ->schema([
                        TagsInput::make('countries')->separator(','),
                        TagsInput::make('categories')->separator(','),
                        TagsInput::make('currencies')->separator(','),
                        TagsInput::make('languages')->separator(','),
                        TagsInput::make('salary_types')->separator(','),
                        TagsInput::make('badges')->separator(','),
                        TagsInput::make('candidate_types')->separator(',')->columnSpanFull(),
                        TagsInput::make('employer_types')->separator(',')->columnSpanFull(),
                    ]),
                Section::make('Homepage Stats')
                    ->schema([
                        Repeater::make('stats')
                            ->schema([
                                TextInput::make('value')->required()->maxLength(40),
                                TextInput::make('label')->required()->maxLength(80),
                            ])
                            ->columns(2)
                            ->reorderable()
                            ->addActionLabel('Add stat'),
                    ]),
                Section::make('Jobs')
                    ->description('These power the jobs index, job detail pages, homepage job cards, and SEO previews.')
                    ->schema([
                        Repeater::make('jobs')
                            ->schema([
                                TextInput::make('slug')->required()->maxLength(120),
                                TextInput::make('title')->required()->maxLength(160),
                                TextInput::make('company')->required()->maxLength(120),
                                TextInput::make('city')->maxLength(80),
                                TextInput::make('country')->maxLength(80),
                                TextInput::make('mode')->maxLength(60),
                                TextInput::make('salary')->maxLength(120),
                                TextInput::make('type')->maxLength(80),
                                TextInput::make('category')->maxLength(100),
                                TextInput::make('deadline')->maxLength(40),
                                TextInput::make('vacancies')->numeric()->minValue(0),
                                Toggle::make('urgent'),
                                TagsInput::make('badges')->separator(',')->columnSpanFull(),
                                Textarea::make('description')->rows(3)->columnSpanFull(),
                                TagsInput::make('responsibilities')->separator(',')->columnSpanFull(),
                                TagsInput::make('skills')->separator(',')->columnSpanFull(),
                                TagsInput::make('requirements')->separator(',')->columnSpanFull(),
                                TagsInput::make('benefits')->separator(',')->columnSpanFull(),
                            ])
                            ->columns(3)
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                            ->addActionLabel('Add job'),
                    ]),
                Section::make('Candidates')
                    ->schema([
                        Repeater::make('candidates')
                            ->schema([
                                TextInput::make('name')->required()->maxLength(120),
                                TextInput::make('headline')->maxLength(180),
                                TextInput::make('country')->maxLength(80),
                                TagsInput::make('badges')->separator(',')->columnSpanFull(),
                                TagsInput::make('skills')->separator(',')->columnSpanFull(),
                            ])
                            ->columns(3)
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                            ->addActionLabel('Add candidate'),
                    ]),
                Section::make('Employer Packages')
                    ->schema([
                        Repeater::make('packages')
                            ->schema([
                                TextInput::make('name')->required()->maxLength(120),
                                TextInput::make('price')->maxLength(80),
                                TagsInput::make('features')->separator(',')->columnSpanFull(),
                            ])
                            ->columns(2)
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                            ->addActionLabel('Add package'),
                    ]),
                Section::make('Policies and SEO Pages')
                    ->columns(2)
                    ->schema([
                        Repeater::make('policies')
                            ->schema([
                                TextInput::make('slug')->required()->maxLength(120),
                                TextInput::make('title')->required()->maxLength(160),
                            ])
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                            ->addActionLabel('Add policy'),
                        Repeater::make('seo_pages')
                            ->schema([
                                TextInput::make('slug')->required()->maxLength(120),
                                TextInput::make('title')->required()->maxLength(160),
                                Textarea::make('focus')->rows(3),
                            ])
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                            ->addActionLabel('Add SEO page'),
                    ]),
                Section::make('Roadmap, Ecosystem, Blog')
                    ->schema([
                        Repeater::make('roadmap')
                            ->schema([
                                TextInput::make('phase')->required()->maxLength(80),
                                TextInput::make('title')->required()->maxLength(120),
                                TagsInput::make('items')->separator(',')->columnSpanFull(),
                            ])
                            ->columns(2)
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['phase'] ?? null),
                        TagsInput::make('ecosystem_modules')->separator(','),
                        TagsInput::make('blog_categories')->separator(','),
                        TagsInput::make('blog_topics')->separator(','),
                    ]),
            ]);
    }

    public function save(): void
    {
        /** @var array<string, mixed> $state */
        $state = $this->form->getState();

        PortalData::save(PortalData::fromFormState($state));

        Notification::make()
            ->success()
            ->title('Portal content saved')
            ->body('Public jobs, packages, SEO pages, policies, and reference data now use the updated content.')
            ->send();
    }
}
