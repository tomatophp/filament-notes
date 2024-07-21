<?php

namespace TomatoPHP\FilamentNotes\Livewire;

use TomatoPHP\FilamentNotes\Filament\Forms\NoteForm;
use TomatoPHP\FilamentNotes\Models\Note;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Notifications\Notification;
use Filament\Support\Contracts\TranslatableContentDriver;
use Livewire\Component;
use TomatoPHP\FilamentCms\Infolists\Components\MarkdownEntry;
use TomatoPHP\FilamentIcons\Components\IconPicker;
use Filament\Forms;

class NoteAction extends Component implements HasActions, HasForms, HasInfolists
{
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithInfolists;

    public ?Note $note=null;

    public function getNoteViewAction(): Action
    {
        return Action::make('getNoteViewAction')
            ->hidden(fn() => $this->note === null)
            ->modalFooterActions(function (){
                return [
                    Action::make('getNoteEditAction')
                        ->iconButton()
                        ->icon('heroicon-o-pencil-square')
                        ->tooltip('Edit')
                        ->color('warning')
                        ->label('Edit')
                        ->fillForm($this->note->toArray())
                        ->cancelParentActions()
                        ->form(NoteForm::make())
                        ->action(function (array $data) {
                           $this->note->update($data);

                           Notification::make()
                                 ->title('Note Updated')
                                 ->body('The note has been updated successfully.')
                                 ->success()
                                 ->send();
                        }),
                    Action::make('getNoteDeleteAction')
                        ->requiresConfirmation()
                        ->iconButton()
                        ->icon('heroicon-o-trash')
                        ->tooltip('Delete')
                        ->color('danger')
                        ->label('Delete')
                        ->cancelParentActions()
                        ->action(function () {
                            $this->note->delete();

                            $this->dispatch('note_deleted');

                            Notification::make()
                                ->title('Note Deleted')
                                ->body('The note has been deleted successfully.')
                                ->success()
                                ->send();
                        }),
                ];
            })
            ->modalHeading('')
            ->modalContent(function (){
                return view('filament-notes::note-view', ['note' => $this->note]);
            })
            ->view('filament-notes::note-action', [
                'note' => $this->note,
            ]);
    }

    public function render()
    {
        return view('filament-notes::livewire.note-action');
    }

    public function makeFilamentTranslatableContentDriver(): ?TranslatableContentDriver
    {
        // TODO: Implement makeFilamentTranslatableContentDriver() method.
    }
}
