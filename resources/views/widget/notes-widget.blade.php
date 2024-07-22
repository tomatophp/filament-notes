@php
 $notes = \TomatoPHP\FilamentNotes\Models\Note::query()
    ->where('is_public', 0)
    ->where('is_pined', 1)
    ->where('user_id', auth()->user()->id)
    ->orWhere('is_public', 0)
    ->where('is_pined', 1)
    ->whereHas('noteMetas', function ($q){
        $q->where('key', "App\Models\User")
          ->where('value',(string)auth()->user()->id);
    })
    ->orWhere('is_public', 1)
    ->where('is_pined', 1)
    ->orderBy('created_at', 'desc')
    ->limit(filament('filament-notes')->widgetLimit)
    ->get();
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
