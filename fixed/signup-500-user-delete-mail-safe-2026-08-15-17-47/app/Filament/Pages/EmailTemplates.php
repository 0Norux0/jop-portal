<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Domain\Identity\Enums\Permission;
use App\Support\EmailContent;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class EmailTemplates extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-envelope';

    protected static string | \UnitEnum | null $navigationGroup = 'Website';

    protected static ?string $navigationLabel = 'Email Templates';

    protected static ?string $title = 'Email Templates';

    protected static ?int $navigationSort = 4;

    protected string $view = 'filament.pages.email-templates';

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
        $this->form->fill(EmailContent::load());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                $this->emailSection('Forgot password email', 'reset_password', hasBody: false),
                $this->emailSection('Email confirmation', 'verify_email', hasBody: false),
                $this->emailSection('Welcome email', 'welcome', hasBody: true),
            ]);
    }

    public function save(): void
    {
        /** @var array<string, mixed> $data */
        $data = $this->form->getState();

        EmailContent::save($data);

        Notification::make()
            ->success()
            ->title('Email templates saved')
            ->body('The website will use the updated email wording immediately.')
            ->send();
    }

    private function emailSection(string $label, string $key, bool $hasBody): Section
    {
        $fields = [
            TextInput::make("{$key}.subject")->required()->maxLength(160),
            TextInput::make("{$key}.eyebrow")->required()->maxLength(80),
            TextInput::make("{$key}.heading")
                ->required()
                ->maxLength(180)
                ->helperText('Use {name} where you want the user name to appear.'),
            Textarea::make("{$key}.intro")->required()->rows(3)->columnSpanFull(),
        ];

        if ($hasBody) {
            $fields[] = Textarea::make("{$key}.body")->required()->rows(3)->columnSpanFull();
        }

        $fields[] = TextInput::make("{$key}.button_label")->required()->maxLength(80);
        $fields[] = Textarea::make("{$key}.note")->rows(2)->columnSpanFull();
        $fields[] = Textarea::make("{$key}.footer")->required()->rows(2)->columnSpanFull();

        return Section::make($label)
            ->description('Edit the words shown in this email. The logo comes from Website Customization.')
            ->columns(2)
            ->schema($fields);
    }
}
