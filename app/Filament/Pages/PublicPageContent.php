<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Domain\Identity\Enums\Permission;
use App\Support\PageContent;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class PublicPageContent extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-duplicate';

    protected static string | \UnitEnum | null $navigationGroup = 'Website';

    protected static ?string $navigationLabel = 'Public Page Text';

    protected static ?string $title = 'Public Page Text';

    protected static ?int $navigationSort = 3;

    protected string $view = 'filament.pages.public-page-content';

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
        $this->form->fill(['pages' => PageContent::toFormState()]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Section::make('Public page hero text')
                    ->description('Edit the main title, eyebrow, and introduction for each public page.')
                    ->schema([
                        Repeater::make('pages')
                            ->schema([
                                TextInput::make('key')->required()->maxLength(80),
                                TextInput::make('eyebrow')->maxLength(120),
                                TextInput::make('title')->required()->maxLength(180),
                                Textarea::make('description')->rows(3)->columnSpanFull(),
                            ])
                            ->columns(3)
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null),
                    ]),
            ]);
    }

    public function save(): void
    {
        /** @var array<string, mixed> $state */
        $state = $this->form->getState();

        PageContent::save(PageContent::fromFormState($state['pages'] ?? []));

        Notification::make()
            ->success()
            ->title('Public page text saved')
            ->send();
    }
}
