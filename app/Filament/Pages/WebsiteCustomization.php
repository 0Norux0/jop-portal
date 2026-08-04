<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Domain\Identity\Enums\Permission;
use App\Support\SiteContent;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class WebsiteCustomization extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-paint-brush';

    protected static string | \UnitEnum | null $navigationGroup = 'Website';

    protected static ?string $navigationLabel = 'Website Customization';

    protected static ?string $title = 'Website Customization';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.website-customization';

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
        $this->form->fill(SiteContent::load());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Section::make('Brand and Theme')
                    ->description('Change the public logo, favicon, colors, and main brand wording.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('brand.name')->required()->maxLength(120),
                        TextInput::make('brand.tagline')->maxLength(180),
                        TextInput::make('brand.powered_by')->maxLength(180),
                        Textarea::make('brand.description')->rows(3)->columnSpanFull(),
                        ColorPicker::make('brand.primary_color')->required(),
                        ColorPicker::make('brand.secondary_color')->required(),
                        FileUpload::make('brand.logo_path')
                            ->label('Logo')
                            ->disk('public')
                            ->directory('site')
                            ->visibility('public')
                            ->image()
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml'])
                            ->maxSize(2048)
                            ->openable()
                            ->downloadable(),
                        FileUpload::make('brand.favicon_path')
                            ->label('Favicon')
                            ->disk('public')
                            ->directory('site')
                            ->visibility('public')
                            ->image()
                            ->acceptedFileTypes(['image/x-icon', 'image/vnd.microsoft.icon', 'image/png', 'image/jpeg', 'image/webp', 'image/svg+xml'])
                            ->maxSize(1024)
                            ->openable()
                            ->downloadable(),
                    ]),
                Section::make('Navigation')
                    ->description('Rename account buttons and manage the public header menu links.')
                    ->columns(3)
                    ->schema([
                        TextInput::make('navigation.home_label')->required()->maxLength(40),
                        TextInput::make('navigation.about_label')->required()->maxLength(40),
                        TextInput::make('navigation.jobs_label')->required()->maxLength(40),
                        TextInput::make('navigation.contact_label')->required()->maxLength(40),
                        TextInput::make('navigation.sign_in_label')->required()->maxLength(40),
                        TextInput::make('navigation.register_label')->required()->maxLength(40),
                        Repeater::make('navigation.links')
                            ->label('Header menu links')
                            ->schema([
                                TextInput::make('label')->required()->maxLength(60),
                                TextInput::make('url')->required()->maxLength(255),
                                Toggle::make('enabled')->default(true),
                            ])
                            ->columns(3)
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['label'] ?? null)
                            ->columnSpanFull(),
                    ]),
                Section::make('Homepage Hero')
                    ->description('Change the first screen of the website.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('home.eyebrow')->maxLength(180),
                        TextInput::make('home.headline')->required()->maxLength(120),
                        Textarea::make('home.description')->rows(4)->columnSpanFull(),
                        TextInput::make('home.keyword_placeholder')->required()->maxLength(60),
                        TextInput::make('home.location_placeholder')->required()->maxLength(60),
                        TextInput::make('home.search_button_label')->required()->maxLength(40),
                        FileUpload::make('home.hero_image_path')
                            ->label('Hero image')
                            ->disk('public')
                            ->directory('site')
                            ->visibility('public')
                            ->image()
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml'])
                            ->maxSize(4096)
                            ->openable()
                            ->downloadable()
                            ->columnSpanFull(),
                    ]),
                Section::make('Homepage Sections')
                    ->description('Edit visible section headings, descriptions, and show/hide homepage sections.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('home.featured_jobs_subheading')->required()->maxLength(80),
                        TextInput::make('home.featured_jobs_heading')->required()->maxLength(120),
                        TextInput::make('home.employers_heading')->required()->maxLength(120),
                        Textarea::make('home.employers_description')->rows(3),
                        TextInput::make('home.employer_tools_heading')->required()->maxLength(120),
                        Textarea::make('home.employer_tools_description')->rows(3),
                        TextInput::make('home.candidates_heading')->required()->maxLength(120),
                        Textarea::make('home.candidates_description')->rows(3),
                        TextInput::make('home.verified_candidates_subheading')->required()->maxLength(80),
                        TextInput::make('home.verified_candidates_heading')->required()->maxLength(120),
                        TextInput::make('home.categories_subheading')->required()->maxLength(80),
                        TextInput::make('home.categories_heading')->required()->maxLength(120),
                        TextInput::make('home.stories_subheading')->required()->maxLength(80),
                        TextInput::make('home.stories_heading')->required()->maxLength(120),
                        Textarea::make('home.stories_description')->rows(3)->columnSpanFull(),
                        Toggle::make('home_sections.stats.enabled')->label('Show stats'),
                        Toggle::make('home_sections.jobs.enabled')->label('Show featured jobs'),
                        Toggle::make('home_sections.employers.enabled')->label('Show featured employers'),
                        Toggle::make('home_sections.candidate_pitch.enabled')->label('Show candidate/employer pitch'),
                        Toggle::make('home_sections.verified_candidates.enabled')->label('Show verified candidates'),
                        Toggle::make('home_sections.categories.enabled')->label('Show categories'),
                        Toggle::make('home_sections.stories.enabled')->label('Show success stories'),
                        Toggle::make('home_sections.how_it_works.enabled')->label('Show how it works'),
                    ]),
                Section::make('Footer and Contact')
                    ->description('Change footer copy, headings, links, and public contact links.')
                    ->columns(2)
                    ->schema([
                        Textarea::make('footer.description')->rows(3)->columnSpanFull(),
                        TextInput::make('footer.powered_by')->maxLength(180),
                        TextInput::make('footer.platform_heading')->required()->maxLength(60),
                        TextInput::make('footer.policies_heading')->required()->maxLength(60),
                        TextInput::make('footer.copyright')->maxLength(180),
                        Repeater::make('footer.platform_links')
                            ->label('Footer platform links')
                            ->schema([
                                TextInput::make('label')->required()->maxLength(80),
                                TextInput::make('url')->required()->maxLength(255),
                                Toggle::make('enabled')->default(true),
                            ])
                            ->columns(3)
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['label'] ?? null)
                            ->columnSpanFull(),
                        Repeater::make('footer.policy_links')
                            ->label('Footer policy links')
                            ->schema([
                                TextInput::make('label')->required()->maxLength(80),
                                TextInput::make('url')->required()->maxLength(255),
                                Toggle::make('enabled')->default(true),
                            ])
                            ->columns(3)
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['label'] ?? null)
                            ->columnSpanFull(),
                        TextInput::make('contact.email')->email()->maxLength(120),
                        TextInput::make('contact.phone')->maxLength(80),
                        TextInput::make('contact.whatsapp_url')->url()->maxLength(255),
                        TextInput::make('contact.facebook_url')->url()->maxLength(255),
                        TextInput::make('contact.linkedin_url')->url()->maxLength(255),
                    ]),
            ]);
    }

    public function save(): void
    {
        /** @var array<string, mixed> $data */
        $data = $this->form->getState();

        SiteContent::save($data);

        Notification::make()
            ->success()
            ->title('Website customization saved')
            ->body('The public site now uses the updated dynamic content.')
            ->send();
    }
}
