<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Domain\Identity\Enums\Permission;
use App\Support\MaintenanceMode as MaintenanceSettings;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class MaintenanceMode extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static string | \UnitEnum | null $navigationGroup = 'Platform';

    protected static ?string $navigationLabel = 'Maintenance Mode';

    protected static ?string $title = 'Maintenance Mode';

    protected static ?int $navigationSort = 20;

    protected string $view = 'filament.pages.maintenance-mode';

    /**
     * @var array<string, mixed> | null
     */
    public ?array $data = [];

    public static function canAccess(): bool
    {
        return Auth::user()?->can(Permission::EnterMaintenanceMode->value) ?? false;
    }

    public function mount(): void
    {
        $this->form->fill(MaintenanceSettings::settings());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Section::make('Site Maintenance')
                    ->description('Temporarily show a maintenance screen to public visitors while admins can still access the panel.')
                    ->schema([
                        Toggle::make('enabled')->label('Enable maintenance mode'),
                        Textarea::make('message')->rows(4)->required(),
                        TagsInput::make('allowed_paths')
                            ->separator(',')
                            ->helperText('Paths that stay open while maintenance mode is on, for example admin, admin/*, login.'),
                    ]),
            ]);
    }

    public function save(): void
    {
        /** @var array<string, mixed> $state */
        $state = $this->form->getState();
        MaintenanceSettings::save($state);

        Notification::make()
            ->success()
            ->title('Maintenance settings saved')
            ->send();
    }
}
