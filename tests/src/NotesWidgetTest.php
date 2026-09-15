<?php

use TomatoPHP\FilamentNotes\Filament\Widgets\NotesWidget;
use TomatoPHP\FilamentNotes\Tests\Database\Factories\NoteFactory;
use TomatoPHP\FilamentNotes\Tests\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    actingAs($this->user);
});

it('renders the widget with pinned notes', function () {
    NoteFactory::new()->pinned()->create(['user_id' => $this->user->id, 'title' => 'Pinned note']);

    livewire(NotesWidget::class)
        ->assertSuccessful()
        ->assertSee('Pinned note');
});

it('lists only pinned notes the user can see, up to the widget limit', function () {
    $other = User::factory()->create();

    $pinned = NoteFactory::new()->pinned()->count(5)->create(['user_id' => $this->user->id]);
    NoteFactory::new()->create(['user_id' => $this->user->id]);
    NoteFactory::new()->pinned()->create(['user_id' => $other->id]);

    $notes = livewire(NotesWidget::class)->instance()->getNotes();

    expect($notes)->toHaveCount(4)
        ->and($notes->pluck('user_id')->unique()->all())->toBe([$this->user->id])
        ->and($pinned->pluck('id'))->toContain(...$notes->pluck('id')->all());
});
