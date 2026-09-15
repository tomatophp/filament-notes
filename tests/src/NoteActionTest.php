<?php

use Filament\Actions\Testing\TestAction;
use TomatoPHP\FilamentNotes\Filament\Pages\NotesGroups;
use TomatoPHP\FilamentNotes\Filament\Pages\NotesStatus;
use TomatoPHP\FilamentNotes\Livewire\NoteAction;
use TomatoPHP\FilamentNotes\Models\Note;
use TomatoPHP\FilamentNotes\Tests\Database\Factories\NoteFactory;
use TomatoPHP\FilamentNotes\Tests\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    actingAs($this->user);
});

it('renders a note', function () {
    $note = NoteFactory::new()->create(['user_id' => $this->user->id, 'title' => 'Sticky title']);

    livewire(NoteAction::class, ['note' => $note])
        ->assertSuccessful()
        ->assertSee('Sticky title');
});

it('opens the note view modal', function () {
    $note = NoteFactory::new()->create(['user_id' => $this->user->id, 'checklist' => ['Buy milk' => false]]);

    livewire(NoteAction::class, ['note' => $note])
        ->mountAction('getNoteViewAction')
        ->assertActionMounted('getNoteViewAction')
        ->assertMountedActionModalSee('Buy milk');
});

it('edits the note from the view modal', function () {
    // Opening the type pages seeds the default groups and statuses the form accepts.
    livewire(NotesGroups::class);
    livewire(NotesStatus::class);

    $note = NoteFactory::new()->create(['user_id' => $this->user->id, 'title' => 'Before']);

    livewire(NoteAction::class, ['note' => $note])
        ->callAction([
            TestAction::make('getNoteViewAction'),
            TestAction::make('getNoteEditAction'),
        ], data: [
            'title' => 'After',
        ]);

    // The nested action closes its parent, so nothing stays mounted to assert errors on; check the result instead.

    expect($note->refresh()->title)->toBe('After');
});

it('toggles a checklist item for the owner', function () {
    $note = NoteFactory::new()->create(['user_id' => $this->user->id, 'checklist' => ['Buy milk' => false]]);

    livewire(NoteAction::class, ['note' => $note])
        ->call('updateChecklist', 'Buy milk')
        ->assertDispatched('updateChartData');

    expect($note->refresh()->checklist)->toBe(['Buy milk' => true]);
});

it('does not let other users toggle the checklist', function () {
    $note = NoteFactory::new()->public()->create(['checklist' => ['Buy milk' => false]]);

    livewire(NoteAction::class, ['note' => $note])
        ->call('updateChecklist', 'Buy milk')
        ->assertForbidden();

    expect($note->refresh()->checklist)->toBe(['Buy milk' => false]);
});

it('shares a note through a public link', function () {
    $note = NoteFactory::new()->create(['user_id' => $this->user->id, 'title' => 'Shared note']);

    livewire(NoteAction::class, ['note' => $note])
        ->callAction([
            TestAction::make('getNoteViewAction'),
            TestAction::make('getNoteShareUrl'),
        ]);

    $uuid = $note->noteMetas()->where('key', 'share')->first()->value;

    expect($uuid)->toBeString()->not->toBeEmpty();

    auth()->logout();

    get(route('notes.view', ['note' => $note, 'uuid' => $uuid]))
        ->assertSuccessful()
        ->assertSee('Shared note');
});

it('returns 404 for an unknown share link', function () {
    $note = NoteFactory::new()->create(['user_id' => $this->user->id]);

    get(route('notes.view', ['note' => $note, 'uuid' => 'not-a-share-link']))->assertNotFound();

    $note->meta('share', 'real-link');

    get(route('notes.view', ['note' => $note, 'uuid' => 'not-a-share-link']))->assertNotFound();
});

it('grants other users access to a private note', function () {
    $friend = User::factory()->create();
    $note = NoteFactory::new()->create(['user_id' => $this->user->id]);

    livewire(NoteAction::class, ['note' => $note])
        ->callAction([
            TestAction::make('getNoteViewAction'),
            TestAction::make('getNoteUserAccess'),
        ], data: [
            'model_type' => User::class,
            'model_id' => [(string) $friend->id],
        ]);

    expect(Note::query()->visibleTo($friend->id)->pluck('id')->all())->toBe([$note->id]);
});

it('deletes the note from the view modal', function () {
    $note = NoteFactory::new()->create(['user_id' => $this->user->id]);

    livewire(NoteAction::class, ['note' => $note])
        ->callAction([
            TestAction::make('getNoteViewAction'),
            TestAction::make('getNoteDeleteAction'),
        ])
        ->assertDispatched('note_deleted');

    expect(Note::query()->find($note->id))->toBeNull();
});
