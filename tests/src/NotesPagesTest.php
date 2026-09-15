<?php

use TomatoPHP\FilamentNotes\Filament\Forms\NoteForm;
use TomatoPHP\FilamentNotes\Filament\Pages\NotesGroups;
use TomatoPHP\FilamentNotes\Filament\Pages\NotesStatus;
use TomatoPHP\FilamentNotes\Tests\Models\User;
use TomatoPHP\FilamentTypes\Models\Type;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

beforeEach(function () {
    actingAs(User::factory()->create());
});

it('renders the groups page and seeds the default groups', function () {
    get(NotesGroups::getUrl())->assertSuccessful();

    livewire(NotesGroups::class)
        ->loadTable()
        ->assertSuccessful()
        ->assertCountTableRecords(3);

    expect(Type::query()->where('for', 'notes')->where('type', 'groups')->pluck('key')->sort()->values()->all())
        ->toBe(['ideas', 'saved', 'todo']);
});

it('renders the status page and seeds the default statuses', function () {
    get(NotesStatus::getUrl())->assertSuccessful();

    livewire(NotesStatus::class)
        ->loadTable()
        ->assertSuccessful()
        ->assertCountTableRecords(3);

    expect(Type::query()->where('for', 'notes')->where('type', 'status')->pluck('key')->sort()->values()->all())
        ->toBe(['done', 'pending', 'processing']);
});

it('offers the seeded types with their translated names in the note form', function () {
    livewire(NotesGroups::class);
    livewire(NotesStatus::class);

    expect(NoteForm::typeOptions('groups'))->toBe(['todo' => 'TODO', 'ideas' => 'Ideas', 'saved' => 'Saved'])
        ->and(NoteForm::typeOptions('status'))->toBe(['pending' => 'Pending', 'processing' => 'Processing', 'done' => 'Done']);
});
