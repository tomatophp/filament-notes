<?php

namespace TomatoPHP\FilamentNotes;

use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use TomatoPHP\FilamentNotes\Console\FilamentNotesInstall;
use TomatoPHP\FilamentNotes\Livewire\NoteAction;

class FilamentNotesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->commands([
            FilamentNotesInstall::class,
        ]);

        $this->mergeConfigFrom(__DIR__.'/../config/filament-notes.php', 'filament-notes');

        $this->publishes([
            __DIR__.'/../config/filament-notes.php' => config_path('filament-notes.php'),
        ], 'filament-notes-config');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'filament-notes-migrations');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'filament-notes');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/filament-notes'),
        ], 'filament-notes-views');

        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'filament-notes');

        $this->publishes([
            __DIR__.'/../resources/lang' => base_path('lang/vendor/filament-notes'),
        ], 'filament-notes-lang');
    }

    public function boot(): void
    {
        Livewire::component('note-action', NoteAction::class);

        FilamentAsset::register([
            Css::make('filament-notes', __DIR__.'/../resources/dist/filament-notes.css'),
        ], 'tomatophp/filament-notes');

        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
    }
}
