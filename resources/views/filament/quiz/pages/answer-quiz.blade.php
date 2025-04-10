{{--<x-filament-panels::page>--}}
{{--    <x-filament-panels::form wire:submit="submit">--}}
{{--    {{ $this->form }}--}}
{{--    <x-filament-panels::form.actions :actions="$this->getFormActions()"  />--}}
{{--    </x-filament-panels::form>--}}
{{--    <x-filament-actions::modals />--}}
{{--</x-filament-panels::page>--}}


<x-filament-panels::page>
    <x-filament-panels::form wire:submit="submitForm">
        {{ $this->form }}
    </x-filament-panels::form>

    <x-filament-panels::form.actions
        :actions="$this->getFormActions()"
    />
</x-filament-panels::page>
