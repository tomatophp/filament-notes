@php
 $notes = \TomatoPHP\FilamentNotes\Models\Note::query()->where('is_pined', 1)->orderBy('created_at', 'desc')->limit($limit)->get();
@endphp
<x-filament-widgets::widget>

    <x-filament::section heading="{{ trans('filament-notes::messages.title') }}" icon="heroicon-o-bookmark">
        <div class="flex flex-wrap gap-4 ">
            @foreach($notes as $key=>$note)
                <div>
                    <livewire:note-action :note="$note" :wire:key="$note->id" />
                </div>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
