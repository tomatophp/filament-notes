<?php

use Filament\Actions\Testing\TestAction;
use TomatoPHP\FilamentNotes\Filament\Pages\NotesGroups;
use TomatoPHP\FilamentNotes\Filament\Pages\NotesStatus;
use TomatoPHP\FilamentNotes\Filament\Resources\NoteResource;
use TomatoPHP\FilamentNotes\Filament\Resources\NoteResource\Pages\ManageNotes;
use TomatoPHP\FilamentNotes\Models\Note;
use TomatoPHP\FilamentNotes\Tests\Database\Factories\NoteFactory;
use TomatoPHP\FilamentNotes\Tests\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    actingAs($this->user);
});

it('renders the notes page', function () {
    NoteFactory::new()->create(['user_id' => $this->user->id, 'title' => 'My sticky note']);

    get(NoteResource::getUrl())
        ->assertSuccessful()
        ->assertSee('My sticky note');
});

it('lists own, public and shared notes only', function () {
    $other = User::factory()->create();

    $own = NoteFactory::new()->create(['user_id' => $this->user->id]);
    $public = NoteFactory::new()->public()->create(['user_id' => $other->id]);
    $shared = NoteFactory::new()->create(['user_id' => $other->id]);
    $shared->noteMetas()->create(['key' => User::class, 'value' => (string) $this->user->id]);
    $private = NoteFactory::new()->create(['user_id' => $other->id]);

    // The table renders sticky-note cards, so check the records the table resolves.
    $visible = livewire(ManageNotes::class)
        ->loadTable()
        ->instance()
        ->getTableRecords()
        ->pluck('id');

    expect($visible->sort()->values()->all())->toBe([$own->id, $public->id, $shared->id])
        ->and($visible)->not->toContain($private->id);
});

it('keeps private notes hidden when a filter is applied', function () {
    $other = User::factory()->create();
    $private = NoteFactory::new()->pinned()->create(['user_id' => $other->id]);
    $own = NoteFactory::new()->pinned()->create(['user_id' => $this->user->id]);

    $visible = livewire(ManageNotes::class)
        ->loadTable()
        ->filterTable('is_pined', true)
        ->instance()
        ->getTableRecords()
        ->pluck('id')
        ->all();

    expect($visible)->toBe([$own->id]);
});

it('shows the groups and status header actions', function () {
    livewire(ManageNotes::class)
        ->assertActionVisible('groups')
        ->assertActionVisible('status');
});

it('creates a note', function () {
    // Opening the type pages seeds the default groups and statuses the form accepts.
    livewire(NotesGroups::class);
    livewire(NotesStatus::class);

    livewire(ManageNotes::class)
        ->callAction('create', data: [
            'title' => 'Created note',
            'body' => '<p>Remember the milk</p>',
            'background' => '#F4F39E',
            'border' => '#DEE184',
            'color' => '#47576B',
            'font_size' => '1.25em',
            'group' => 'todo',
            'status' => 'pending',
            'is_pined' => true,
            'is_public' => false,
        ])
        ->assertHasNoActionErrors();

    assertDatabaseHas('notes', [
        'title' => 'Created note',
        'user_id' => $this->user->id,
        'user_type' => User::class,
        'font_size' => '1.25em',
        'group' => 'todo',
        'is_pined' => true,
    ]);
});

it('edits a note', function () {
    livewire(NotesGroups::class);
    livewire(NotesStatus::class);

    $note = NoteFactory::new()->create(['user_id' => $this->user->id, 'title' => 'Old title']);

    livewire(ManageNotes::class)
        ->loadTable()
        ->callAction(TestAction::make('edit')->table($note), data: [
            'title' => 'New title',
            'status' => 'done',
        ])
        ->assertHasNoActionErrors();

    expect($note->refresh())
        ->title->toBe('New title')
        ->status->toBe('done');
});

it('deletes a note', function () {
    $note = NoteFactory::new()->create(['user_id' => $this->user->id]);

    livewire(ManageNotes::class)
        ->loadTable()
        ->callAction(TestAction::make('delete')->table($note));

    assertDatabaseMissing('notes', ['id' => $note->id]);
    expect(Note::query()->count())->toBe(0);
});
