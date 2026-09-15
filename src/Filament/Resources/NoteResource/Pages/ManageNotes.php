<?php

namespace TomatoPHP\FilamentNotes\Filament\Resources\NoteResource\Pages;

use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use Livewire\Attributes\On;
use TomatoPHP\FilamentNotes\Filament\Pages\NotesGroups;
use TomatoPHP\FilamentNotes\Filament\Pages\NotesStatus;
use TomatoPHP\FilamentNotes\Filament\Resources\NoteResource;
use TomatoPHP\FilamentNotes\FilamentNotesPlugin;

class ManageNotes extends ManageRecords
{
    protected static string $resource = NoteResource::class;

    #[On('note_deleted')]
    public function noteDeleted(): void
    {
        $this->resetTable();
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->mutateDataUsing(function (array $data): array {
                $data['user_id'] = auth()->id();
                $data['user_type'] = config('filament-notes.models.user');

                return $data;
            }),
            Action::make('status')
                ->hidden(fn (): bool => ! FilamentNotesPlugin::get()->useStatus)
                ->hiddenLabel()
                ->color('warning')
                ->tooltip(trans('filament-notes::messages.pages.status'))
                ->url(fn (): string => NotesStatus::getUrl())
                ->icon('heroicon-o-check-badge'),
            Action::make('groups')
                ->hidden(fn (): bool => ! FilamentNotesPlugin::get()->useGroups)
                ->hiddenLabel()
                ->color('success')
                ->tooltip(trans('filament-notes::messages.pages.groups'))
                ->url(fn (): string => NotesGroups::getUrl())
                ->icon('heroicon-o-rectangle-group'),
        ];
    }
}
