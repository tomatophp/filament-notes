<div class="p-6">
    <div class="flex flex-wrap gap-4">
        @foreach($records as $note)
            <livewire:note-action :note="$note" :key="'note-table-'.$note->id" />
        @endforeach
    </div>
</div>
