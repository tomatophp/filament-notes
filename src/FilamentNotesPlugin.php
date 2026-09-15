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
    public int $widgetLimit = 4;

    public bool $useGroups = false;

    public bool $useStatus = false;

    public bool $useNotification = false;

    public bool $useUserAccess = false;

    public bool $useShareLink = false;

    public bool $useCheckList = false;

    public string $navigationIcon = 'heroicon-o-bookmark';

    public function getId(): string
    {
        return 'filament-notes';
    }

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

    public function useCheckList(bool $condition = true): static
    {
        $this->useCheckList = $condition;

        return $this;
    }

    public function useNotification(bool $condition = true): static
    {
        $this->useNotification = $condition;

        return $this;
    }

    public function useUserAccess(bool $condition = true): static
    {
        $this->useUserAccess = $condition;

        return $this;
    }

    public function useShareLink(bool $condition = true): static
    {
        $this->useShareLink = $condition;

        return $this;
    }

    public function useGroups(bool $condition = true): static
    {
        $this->useGroups = $condition;

        return $this;
    }

    public function useStatus(bool $condition = true): static
    {
        $this->useStatus = $condition;

        return $this;
    }

    public function register(Panel $panel): void
    {
        $panel
            ->resources([
                NoteResource::class,
            ])
            ->pages([
                NotesGroups::class,
                NotesStatus::class,
            ])
            ->livewireComponents([
                NoteAction::class,
            ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }
}
