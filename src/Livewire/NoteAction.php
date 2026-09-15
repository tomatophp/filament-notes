<?php

namespace TomatoPHP\FilamentNotes\Livewire;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\View as SchemaView;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Js;
use Illuminate\Support\Str;
use Livewire\Component;
use TomatoPHP\FilamentNotes\Filament\Forms\NoteForm;
use TomatoPHP\FilamentNotes\FilamentNotesPlugin;
use TomatoPHP\FilamentNotes\Models\Note;
use TomatoPHP\FilamentNotes\Models\NoteMeta;

class NoteAction extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public ?Note $note = null;

    public function isOwner(): bool
    {
        return $this->note !== null
            && auth()->check()
            && (string) $this->note->user_id === (string) auth()->id();
    }

    /**
     * Doughnut chart data for the note checklist (done vs pending).
     *
     * @return array<string, mixed>
     */
    public function getCachedData(): array
    {
        $checklist = collect($this->note?->checklist ?? []);

        return [
            'labels' => [
                trans('filament-notes::messages.actions.checklist.state.done'),
                trans('filament-notes::messages.actions.checklist.state.pending'),
            ],
            'datasets' => [
                [
                    'data' => [
                        $checklist->filter(fn (mixed $item): bool => (bool) $item)->count(),
                        $checklist->reject(fn (mixed $item): bool => (bool) $item)->count(),
                    ],
                    'backgroundColor' => [
                        'rgb(16 185 129)',
                        'rgb(244 63 94)',
                    ],
                    'hoverOffset' => 0,
                ],
            ],
        ];
    }

    public function updateChecklist(string $key): void
    {
        abort_unless($this->isOwner(), 403);

        $list = $this->note->checklist ?? [];
        if (array_key_exists($key, $list)) {
            $list[$key] = empty($list[$key]);
        }

        $this->note->checklist = $list;
        $this->note->save();

        $this->dispatch('updateChartData', data: $this->getCachedData());

        Notification::make()
            ->title(trans('filament-notes::messages.actions.checklist.notification.updated.title'))
            ->body(trans('filament-notes::messages.actions.checklist.notification.updated.body'))
            ->success()
            ->send();
    }

    public function getNoteViewAction(): Action
    {
        return Action::make('getNoteViewAction')
            ->label(trans('filament-notes::messages.actions.view'))
            ->hidden(fn (): bool => $this->note === null)
            ->modalHeading('')
            ->modalFooterActions(fn (): array => $this->isOwner() ? $this->getOwnerActions() : [])
            // A schema (not modalContent) so nested owner actions can return to this modal.
            ->schema([
                SchemaView::make('filament-notes::note-view')
                    ->viewData(fn (): array => [
                        'note' => $this->note,
                        'checklistChartData' => $this->getCachedData(),
                    ]),
            ])
            ->view('filament-notes::note-action', [
                'note' => $this->note,
                'checklistChartData' => $this->getCachedData(),
            ]);
    }

    /**
     * @return array<Action>
     */
    protected function getOwnerActions(): array
    {
        $plugin = FilamentNotesPlugin::get();
        $userModel = config('filament-notes.models.user');

        return [
            Action::make('getNoteChecklist')
                ->hidden(! $plugin->useCheckList)
                ->iconButton()
                ->tooltip(trans('filament-notes::messages.actions.checklist.label'))
                ->label(trans('filament-notes::messages.actions.checklist.label'))
                ->fillForm(fn (): array => [
                    'checklist' => $this->note->checklist ?? [],
                ])
                ->schema([
                    KeyValue::make('checklist')
                        ->label(trans('filament-notes::messages.actions.checklist.form.checklist'))
                        ->editableValues(false),
                ])
                ->cancelParentActions()
                ->action(function (array $data): void {
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
                ->hidden(! $plugin->useNotification)
                ->iconButton()
                ->icon('heroicon-o-bell')
                ->tooltip(trans('filament-notes::messages.actions.notify.label'))
                ->label(trans('filament-notes::messages.actions.notify.label'))
                ->schema([
                    Select::make('users')
                        ->label(trans('filament-notes::messages.actions.user_access.form.model_id'))
                        ->searchable()
                        ->multiple()
                        ->required()
                        ->options(fn (): array => $userModel::query()->pluck('name', 'id')->toArray()),
                ])
                ->cancelParentActions()
                ->action(function (array $data) use ($userModel): void {
                    $notification = Notification::make()
                        ->title($this->note->title ?: trans('filament-notes::messages.single'))
                        ->body(Str::limit(strip_tags((string) $this->note->body), 250))
                        ->icon($this->note->icon ?: 'heroicon-o-bookmark');

                    foreach ($userModel::query()->whereKey($data['users'])->get() as $user) {
                        $notification->sendToDatabase($user);
                    }

                    Notification::make()
                        ->title(trans('filament-notes::messages.actions.notify.notification.title'))
                        ->body(trans('filament-notes::messages.actions.notify.notification.body'))
                        ->success()
                        ->send();
                }),
            Action::make('getNoteShareUrl')
                ->hidden(! $plugin->useShareLink)
                ->iconButton()
                ->icon('heroicon-o-link')
                ->tooltip(trans('filament-notes::messages.actions.share.label'))
                ->label(trans('filament-notes::messages.actions.share.label'))
                ->cancelParentActions()
                ->action(function (): void {
                    $uuid = (string) Str::uuid();
                    $this->note->meta('share', $uuid);

                    $url = route('notes.view', [
                        'note' => $this->note,
                        'uuid' => $uuid,
                    ]);

                    $this->js('window.navigator.clipboard.writeText('.Js::from($url).')');

                    Notification::make()
                        ->title(trans('filament-notes::messages.actions.share.notification.title'))
                        ->body(trans('filament-notes::messages.actions.share.notification.body'))
                        ->success()
                        ->send();
                }),
            Action::make('getNoteUserAccess')
                ->hidden(fn (): bool => $this->note->is_public || ! $plugin->useUserAccess)
                ->iconButton()
                ->icon('heroicon-o-user')
                ->tooltip(trans('filament-notes::messages.actions.user_access.label'))
                ->label(trans('filament-notes::messages.actions.user_access.label'))
                ->fillForm(fn (): array => [
                    'model_type' => $userModel,
                    'model_id' => $this->note->noteMetas()->where('key', $userModel)->pluck('value')->map(fn (mixed $id): string => (string) $id)->toArray(),
                ])
                ->schema([
                    Select::make('model_type')
                        ->label(trans('filament-notes::messages.actions.user_access.form.model_type'))
                        ->required()
                        ->options([
                            $userModel => 'Users',
                        ])
                        ->live()
                        ->searchable(),
                    Select::make('model_id')
                        ->label(trans('filament-notes::messages.actions.user_access.form.model_id'))
                        ->searchable()
                        ->multiple()
                        ->hidden(fn (Get $get): bool => ! $get('model_type'))
                        ->options(fn (Get $get): array => $get('model_type') ? $get('model_type')::query()->whereKeyNot(auth()->id())->pluck('name', 'id')->toArray() : []),
                ])
                ->cancelParentActions()
                ->action(function (array $data) use ($userModel): void {
                    $userIds = collect($data['model_id'] ?? [])->map(fn (mixed $id): string => (string) $id);

                    $this->note->noteMetas()
                        ->where('key', $userModel)
                        ->get()
                        ->reject(fn (NoteMeta $meta): bool => $userIds->contains((string) $meta->value))
                        ->each->delete();

                    foreach ($userIds as $userId) {
                        $this->note->noteMetas()->firstOrCreate(
                            ['key' => $userModel, 'value' => $userId],
                        );
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
                ->fillForm(fn (): array => $this->note->toArray())
                ->cancelParentActions()
                ->schema(NoteForm::make())
                ->action(function (array $data): void {
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
                ->action(function (): void {
                    $this->note->delete();

                    $this->dispatch('note_deleted');

                    Notification::make()
                        ->title(trans('filament-notes::messages.notifications.delete.title'))
                        ->body(trans('filament-notes::messages.notifications.delete.body'))
                        ->success()
                        ->send();
                }),
        ];
    }

    public function render(): View
    {
        return view('filament-notes::livewire.note-action');
    }
}
