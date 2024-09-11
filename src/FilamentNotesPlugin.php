<?php

namespace TomatoPHP\FilamentNotes;

use Filament\Contracts\Plugin;
use Filament\Panel;
use TomatoPHP\FilamentNotes\Filament\Pages\NotesGroups;
use TomatoPHP\FilamentNotes\Filament\Pages\NotesStatus;
use TomatoPHP\FilamentNotes\Filament\Resources\NoteResource;
use TomatoPHP\FilamentNotes\Livewire\NoteAction;

class FilamentNotesPlugin implements Plugin
{
    public function getId(): string
    {
        return 'filament-notes';
    }

    public int $widgetLimit = 4;
    public bool $useGroups = false;
    public bool $useStatus = false;
    public bool $useNotification = false;
    public bool $useUserAccess = false;
    public bool $useShareLink = false;
    public bool $useCheckList = false;
    public string $navigationIcon = 'heroicon-o-bookmark';

    public function navigationIcon(string $icon): static
    {
        $this->navigationIcon = $icon;
        return $this;
    }

    public function widgetLimit(int $limit): static
    {
        $this->widgetLimit = $limit;
        return $this;
    }

    public function useCheckList(bool $condation = true): static
    {
        $this->useCheckList = $condation;
        return $this;
    }

    public function useNotification(bool $condation = true): static
    {
        $this->useNotification = $condation;
        return $this;
    }

    public function useUserAccess(bool $condation = true): static
    {
        $this->useUserAccess = $condation;
        return $this;
    }

    public function useShareLink(bool $condation = true): static
    {
        $this->useShareLink = $condation;
        return $this;
    }

    public function useGroups(bool $condation = true): static
    {
        $this->useGroups = $condation;
        return $this;
    }

    public function useStatus(bool $condation = true): static
    {
        $this->useStatus = $condation;
        return $this;
    }

    public function register(Panel $panel): void
    {
        $panel->resources([
            NoteResource::class,
        ])
        ->pages([
            NotesGroups::class,
            NotesStatus::class,
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
