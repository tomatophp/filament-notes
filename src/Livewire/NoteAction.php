<?php

namespace TomatoPHP\FilamentNotes\Livewire;

use App\Models\User;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use TomatoPHP\FilamentAlerts\Services\SendNotification;
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
    public array $checkListValue = [];

    #[On('note_deleted')]
    public function noteDeleted(): void
    {
        $this->dispatch('$refresh');
    }

    public function getCachedData(): array
    {
        return [
            'labels' => [trans('filament-notes::messages.actions.checklist.state.done'), trans('filament-notes::messages.actions.checklist.state.pending')],
            'datasets' => [
                [
                    'data' => [collect($this->note->checklist)->filter(fn($item) => $item)->count(), collect($this->note->checklist)->filter(fn($item) => !$item)->count()],
                    'backgroundColor' => [
                        'rgb(16 185 129)',
                        'rgb(244 63 94)'
                    ],
                    'hoverOffset' => 0
                ]
            ]
        ];
    }

    public function updateChecklist(string $key)
    {
        $list = $this->note->checklist;
        foreach ($list as $index=>$item){
            if($index === $key){
                $list[$index] = empty($item) ? true : false;
            }
        }
        $this->note->checklist = $list;
        $this->note->save();

        $this->dispatch('updateChartData', data: $this->getCachedData());

        Notification::make()
            ->title(trans('filament-notes::messages.actions.checklist.notification.updated.title'))
            ->body(trans('filament-notes::messages.actions.checklist.notification.updated.body'))
            ->success()
            ->send();

        $this->dispatch('note_deleted');
    }

    public function getNoteViewAction(): Action
    {
        return Action::make('getNoteViewAction')
            ->label(trans('filament-notes::messages.actions.view'))
            ->hidden(fn() => $this->note === null)
            ->modalFooterActions(function (){
                return $this->note->user_id === auth()->user()->id ? [
                    Action::make('getNoteChecklist')
                        ->hidden(!filament('filament-notes')->useCheckList)
                        ->iconButton()
//                        ->tooltip(trans('filament-notes::messages.actions.checklist.label'))
                        ->label(trans('filament-notes::messages.actions.checklist.label'))
                        ->fillForm([
                            'checklist' => $this->note->checklist ?? [],
                        ])
                        ->form([
                            Forms\Components\KeyValue::make('checklist')
                                ->label(trans('filament-notes::messages.actions.checklist.form.checklist'))
                                ->disableEditingValues(),
                        ])
                        ->action(function (array $data){
                            $this->note->update([
                                'checklist' => $data['checklist'],
                            ]);

                            Notification::make()
                                ->title(trans('filament-notes::messages.actions.checklist.notification.title'))
                                ->body(trans('filament-notes::messages.actions.checklist.notification.body'))
                                ->success()
                                ->send();
                        })
                        ->icon('heroicon-o-check-circle'),
                    Action::make('getNoteNotification')
                        ->hidden(!filament('filament-notes')->useNotification)
                        ->iconButton()
                        ->icon('heroicon-o-bell')
                        ->tooltip(trans('filament-notes::messages.actions.notify.label'))
                        ->label(trans('filament-notes::messages.actions.notify.label'))
                        ->form([
                            Forms\Components\Select::make('providers')
                                ->label(trans('filament-alerts::messages.templates.form.providers'))
                                ->multiple()
                                ->options(collect(config('filament-alerts.providers'))->pluck('name', 'id')->toArray()),
                            Forms\Components\Select::make('privacy')
                                ->label(trans('filament-alerts::messages.notifications.form.privacy'))
                                ->searchable()
                                ->options([
                                    'public' => 'Public',
                                    'private' => 'Private',
                                ])
                                ->live()
                                ->required()
                                ->default('public'),
                            Forms\Components\Select::make('model_type')
                                ->searchable()
                                ->label(trans('filament-alerts::messages.notifications.form.user_type'))
                                ->options(config('filament-alerts.models'))
                                ->required()
                                ->live(),
                            Forms\Components\Select::make('model_id')
                                ->label(trans('filament-alerts::messages.notifications.form.user'))
                                ->searchable()
                                ->hidden(fn (Forms\Get $get): bool => $get('privacy') !== 'private')
                                ->options(fn (Forms\Get $get) => $get('model_type') ? $get('model_type')::pluck('name', 'id')->toArray() : [])
                                ->required(),
                        ])
                        ->cancelParentActions()
                        ->action(function(array $data){
                            $nofity = SendNotification::make($data['providers'])
                                ->title($this->note->title)
                                ->message($this->note->body)
                                ->privacy($data['privacy'])
                                ->model($data['model_type'])
                                ->id($data['privacy'] === 'private' ? $data['model_id'] : null);

                            if($this->note->icon){
                                $nofity->icon($this->note->icon);
                            }

                            $nofity->fire();

                            Notification::make()
                                ->title(trans('filament-notes::messages.actions.notify.notification.title'))
                                ->body(trans('filament-notes::messages.actions.notify.notification.body'))
                                ->success()
                                ->send();
                        }),
                    Action::make('getNoteShareUrl')
                        ->hidden(!filament('filament-notes')->useShareLink)
                        ->iconButton()
                        ->icon('heroicon-o-link')
                        ->tooltip(trans('filament-notes::messages.actions.share.label'))
                        ->label(trans('filament-notes::messages.actions.share.label'))
                        ->action(function(){
                            $uuid = Str::uuid();
                            $this->note->meta('share', $uuid);

                            $this->js('window.navigator.clipboard.writeText("'.route('notes.view', [
                                'note' => $this->note,
                                'uuid' => $uuid,
                                ]).'")');

                            Notification::make()
                                ->title(trans('filament-notes::messages.actions.share.notification.title'))
                                ->body(trans('filament-notes::messages.actions.share.notification.body'))
                                ->success()
                                ->send();
                        }),
                    Action::make('getNoteUserAccess')
                        ->hidden($this->note->is_public || !filament('filament-notes')->useUserAccess)
                        ->iconButton()
                        ->icon('heroicon-o-user')
                        ->tooltip(trans('filament-notes::messages.actions.user_access.label'))
                        ->label(trans('filament-notes::messages.actions.user_access.label'))
                        ->fillForm([
                            'model_type' => User::class,
                            'model_id' => $this->note->noteMetas()->where('key', User::class)->pluck('value')->toArray(),
                        ])
                        ->form([
                            Forms\Components\Select::make('model_type')
                                ->label(trans('filament-notes::messages.actions.user_access.form.model_type'))
                                ->required()
                                ->options([
                                    User::class => 'Users',
                                ])
                                ->live()
                                ->searchable(),
                            Forms\Components\Select::make('model_id')
                                ->label(trans('filament-notes::messages.actions.user_access.form.model_id'))
                                ->searchable()
                                ->multiple()
                                ->hidden(fn (Forms\Get $get): bool => !$get('model_type'))
                                ->options(fn (Forms\Get $get) => $get('model_type') ? $get('model_type')::where('id', '!=', auth()->user()->id)->pluck('name', 'id')->toArray() : []),
                        ])
                        ->action(function(array $data){
                            if(count($data['model_id']) === 0){
                                $this->note->noteMetas()->where('key', User::class)->delete();
                            }
                            foreach ($data['model_id'] as $user){
                                $exists = $this->note->noteMetas()->where('key', $data['model_type'])->where('value', $user)->first();
                                if(!$exists){
                                    $this->note->noteMetas()->create([
                                        'key' => $data['model_type'],
                                        'value' => $user,
                                    ]);
                                }
                            }

                            Notification::make()
                                ->title(trans('filament-notes::messages.actions.user_access.notification.title'))
                                ->body(trans('filament-notes::messages.actions.user_access.notification.body'))
                                ->success()
                                ->send();
                        }),
                    Action::make('getNoteEditAction')
                        ->iconButton()
                        ->icon('heroicon-o-pencil-square')
                        ->tooltip(trans('filament-notes::messages.actions.edit'))
                        ->color('warning')
                        ->label(trans('filament-notes::messages.actions.edit'))
                        ->fillForm($this->note->toArray())
                        ->cancelParentActions()
                        ->form(NoteForm::make())
                        ->action(function (array $data) {
                           $this->note->update($data);

                           Notification::make()
                                 ->title(trans('filament-notes::messages.notifications.edit.title'))
                                 ->body(trans('filament-notes::messages.notifications.edit.body'))
                                 ->success()
                                 ->send();
                        }),
                    Action::make('getNoteDeleteAction')
                        ->requiresConfirmation()
                        ->iconButton()
                        ->icon('heroicon-o-trash')
                        ->tooltip(trans('filament-notes::messages.actions.delete'))
                        ->color('danger')
                        ->label(trans('filament-notes::messages.actions.delete'))
                        ->cancelParentActions()
                        ->action(function () {
                            $this->note->delete();

                            $this->dispatch('note_deleted');

                            Notification::make()
                                ->title(trans('filament-notes::messages.notifications.delete.title'))
                                ->body(trans('filament-notes::messages.notifications.delete.body'))
                                ->success()
                                ->send();
                        }),
                ] : [];
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
