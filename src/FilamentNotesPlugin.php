<?php

namespace TomatoPHP\FilamentNotes;

use Filament\Contracts\Plugin;
use Filament\Panel;
use TomatoPHP\FilamentNotes\Filament\Resources\NoteResource;
use TomatoPHP\FilamentNotes\Livewire\NoteAction;

class FilamentNotesPlugin implements Plugin
{
    public function getId(): string
    {
        return 'filament-notes';
    }

    public int $widgetLimit = 4;

    public function widgetLimit(int $limit): static
    {
        $this->widgetLimit = $limit;
        return $this;
    }

    public function register(Panel $panel): void
    {
        $panel->resources([
            NoteResource::class,
        ])
        ->livewireComponents([
            NoteAction::class
        ]);

    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return new static();
    }
}
