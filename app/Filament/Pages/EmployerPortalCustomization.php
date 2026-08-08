<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Domain\Identity\Enums\Permission;
use App\Support\EmployerPortalContent;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class EmployerPortalCustomization extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-building-office-2';

    protected static string | \UnitEnum | null $navigationGroup = 'Website';

    protected static ?string $navigationLabel = 'Employer Portal';

    protected static ?string $title = 'Employer Portal Customization';

    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.pages.employer-portal-customization';

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
        $this->form->fill(EmployerPortalContent::load());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Section::make('Employer Navigation')
                    ->description('Rename or hide employer workspace menu items.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('navigation.overview.label')->required()->maxLength(60),
                        Toggle::make('navigation.overview.enabled')->label('Show overview'),
                        TextInput::make('navigation.company.label')->required()->maxLength(60),
                        Toggle::make('navigation.company.enabled')->label('Show company page'),
                        TextInput::make('navigation.jobs.label')->required()->maxLength(60),
                        Toggle::make('navigation.jobs.enabled')->label('Show jobs'),
                        TextInput::make('navigation.applicants.label')->required()->maxLength(60),
                        Toggle::make('navigation.applicants.enabled')->label('Show applicants'),
                        TextInput::make('navigation.candidates.label')->required()->maxLength(60),
                        Toggle::make('navigation.candidates.enabled')->label('Show find candidates'),
                        TextInput::make('navigation.billing.label')->required()->maxLength(60),
                        Toggle::make('navigation.billing.enabled')->label('Show admin center'),
                        TextInput::make('navigation.promotion.label')->required()->maxLength(60),
                        Toggle::make('navigation.promotion.enabled')->label('Show advertise'),
                    ]),
                Section::make('Admin Center Text')
                    ->description('Change billing/account labels shown to employers.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('billing.eyebrow')->required()->maxLength(80),
                        TextInput::make('billing.title')->required()->maxLength(120),
                        TextInput::make('billing.owner_label')->required()->maxLength(80),
                        TextInput::make('billing.email_label')->required()->maxLength(80),
                        TextInput::make('billing.plan_label')->required()->maxLength(80),
                        TextInput::make('billing.status_label')->required()->maxLength(80),
                        TextInput::make('billing.save_label')->required()->maxLength(80),
                    ]),
                Section::make('Admin Center Cards')
                    ->description('Change the small cards below employer billing/account settings.')
                    ->columns(3)
                    ->schema([
                        TextInput::make('billing.invoices_title')->required()->maxLength(80),
                        TextInput::make('billing.payment_title')->required()->maxLength(80),
                        TextInput::make('billing.team_title')->required()->maxLength(80),
                        Textarea::make('billing.invoices_copy')->required()->rows(3),
                        Textarea::make('billing.payment_copy')->required()->rows(3),
                        Textarea::make('billing.team_copy')->required()->rows(3),
                    ]),
            ]);
    }

    public function save(): void
    {
        /** @var array<string, mixed> $data */
        $data = $this->form->getState();

        EmployerPortalContent::save($data);

        Notification::make()
            ->success()
            ->title('Employer portal customization saved')
            ->body('Employer workspace labels and account text now use these settings.')
            ->send();
    }
}
