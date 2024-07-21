<?php

namespace TomatoPHP\FilamentNotes\Filament\Resources\NoteResource\Pages;

use TomatoPHP\FilamentNotes\Filament\Resources\NoteResource;
use TomatoPHP\FilamentNotes\Models\Note;
use App\Models\User;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use Livewire\Attributes\On;
use TomatoPHP\FilamentIcons\Components\IconPicker;
use Filament\Forms;

class ManageNotes extends ManageRecords
{
    protected static string $resource = NoteResource::class;

    #[On('note_deleted')]
    public function noteDeleted(): void
    {
        $this->dispatch('$refresh');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->mutateFormDataUsing(function (array $data) {
                $data['user_id'] = auth()->user()->id;
                $data['user_type'] = User::class;
                return $data;
            }),
        ];
    }
}
