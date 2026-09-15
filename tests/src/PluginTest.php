<?php

use Filament\Facades\Filament;
use TomatoPHP\FilamentNotes\Filament\Pages\NotesGroups;
use TomatoPHP\FilamentNotes\Filament\Pages\NotesStatus;
use TomatoPHP\FilamentNotes\Filament\Resources\NoteResource;
use TomatoPHP\FilamentNotes\FilamentNotesPlugin;

it('registers the plugin on the panel', function () {
    $panel = Filament::getPanel('admin');

    expect($panel->getPlugin('filament-notes'))->toBeInstanceOf(FilamentNotesPlugin::class)
        ->and($panel->getResources())->toContain(NoteResource::class)
        ->and($panel->getPages())->toContain(NotesGroups::class, NotesStatus::class);
});

it('has every option off by default', function () {
    $plugin = FilamentNotesPlugin::make();

    expect($plugin->getId())->toBe('filament-notes')
        ->and($plugin->useGroups)->toBeFalse()
        ->and($plugin->useStatus)->toBeFalse()
        ->and($plugin->useNotification)->toBeFalse()
        ->and($plugin->useUserAccess)->toBeFalse()
        ->and($plugin->useShareLink)->toBeFalse()
        ->and($plugin->useCheckList)->toBeFalse()
        ->and($plugin->widgetLimit)->toBe(4)
        ->and($plugin->navigationIcon)->toBe('heroicon-o-bookmark');
});

it('toggles each option', function (string $method, string $property) {
    expect(FilamentNotesPlugin::make()->{$method}()->{$property})->toBeTrue()
        ->and(FilamentNotesPlugin::make()->{$method}()->{$method}(false)->{$property})->toBeFalse();
})->with([
    ['useGroups', 'useGroups'],
    ['useStatus', 'useStatus'],
    ['useNotification', 'useNotification'],
    ['useUserAccess', 'useUserAccess'],
    ['useShareLink', 'useShareLink'],
    ['useCheckList', 'useCheckList'],
]);

it('sets the widget limit and navigation icon', function () {
    $plugin = FilamentNotesPlugin::make()
        ->widgetLimit(8)
        ->navigationIcon('heroicon-o-presentation-chart-line');

    expect($plugin->widgetLimit)->toBe(8)
        ->and($plugin->navigationIcon)->toBe('heroicon-o-presentation-chart-line');
});

it('uses the panel plugin options in the resource', function () {
    FilamentNotesPlugin::get()->navigationIcon('heroicon-o-star');

    expect(NoteResource::getNavigationIcon())->toBe('heroicon-o-star');
});
