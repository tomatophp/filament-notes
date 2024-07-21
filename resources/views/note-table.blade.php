<div class="p-6">
    <div class="flex flex-wrap gap-4">
        @foreach($records as $key=>$note)
            <livewire:note-action :note="$note" :wire:key="$note->id" />
        @endforeach
    </div>
</div>
