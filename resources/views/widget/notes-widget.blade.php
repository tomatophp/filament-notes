<x-filament-widgets::widget>
    <x-filament::section heading="{{ trans('filament-notes::messages.title') }}" icon="heroicon-o-bookmark">
        <div class="flex flex-wrap gap-4">
            @foreach($this->getNotes() as $note)
                <div wire:key="notes-widget-{{ $note->id }}">
                    <livewire:note-action :note="$note" :key="'notes-widget-note-'.$note->id" />
                </div>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
