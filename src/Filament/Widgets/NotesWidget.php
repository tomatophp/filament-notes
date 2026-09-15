<?php

namespace TomatoPHP\FilamentNotes\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\On;
use TomatoPHP\FilamentNotes\FilamentNotesPlugin;
use TomatoPHP\FilamentNotes\Models\Note;

class NotesWidget extends Widget
{
    protected int|string|array $columnSpan = 2;

    protected string $view = 'filament-notes::widget.notes-widget';

    #[On('note_deleted')]
    public function noteDeleted(): void
    {
        // Re-rendering drops the deleted note from the list.
    }

    /**
     * Pinned notes the current user can see, newest first.
     *
     * @return Collection<int, Note>
     */
    public function getNotes(): Collection
    {
        return Note::query()
            ->where('is_pined', true)
            ->visibleTo(auth()->id())
            ->latest()
            ->limit(FilamentNotesPlugin::get()->widgetLimit)
            ->get();
    }
}
