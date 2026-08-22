<x-filament-panels::page>
    <form wire:submit="save" class="space-y-6">
        {{ $this->form }}

        <div class="flex flex-wrap gap-3">
            <x-filament::button type="submit">
                Save email templates
            </x-filament::button>

            <x-filament::button tag="a" href="{{ route('email-previews.index') }}" color="gray">
                Open previews
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
