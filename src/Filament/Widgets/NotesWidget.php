<?php

namespace TomatoPHP\FilamentNotes\Filament\Widgets;

use Filament\Widgets\Widget;
use Livewire\Attributes\On;

class NotesWidget extends Widget
{
    #[On('note_deleted')]
    public function noteDeleted(): void
    {
        $this->dispatch('$refresh');
    }

    protected int | string | array $columnSpan = 2;
    protected static string $view = 'filament-notes::widget.notes-widget';
}
