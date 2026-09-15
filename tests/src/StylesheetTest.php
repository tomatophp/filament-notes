<?php

use Filament\Support\Facades\FilamentAsset;

/**
 * Filament v5 panels only compile the utilities Filament itself uses, so every utility class the
 * note views rely on must be defined in the stylesheet the package ships.
 */
it('registers the notes stylesheet with filament', function () {
    $styles = collect(FilamentAsset::getStyles(['tomatophp/filament-notes']))
        ->map(fn ($asset) => $asset->getId());

    expect($styles)->toContain('filament-notes');
});

it('ships every utility class the note views use', function () {
    $css = file_get_contents(__DIR__.'/../../resources/dist/filament-notes.css');

    $defined = collect(preg_match_all('/\.((?:[a-z0-9-]|\\\\[:\/])+)\s*[{,]/i', $css, $matches) ? $matches[1] : [])
        ->map(fn (string $selector) => stripslashes($selector))
        ->all();

    // Filament component classes, the package's own card classes, and form-control states
    // styled by Filament's checkbox component are not utilities the package has to ship.
    $ignored = '/^(fi-|note$|post-it$|taped_note|apply-font$|prose$|checked:|dark:checked:|dark:disabled:|disabled:|focus:|dark:focus:)/';

    $used = collect(glob(__DIR__.'/../../resources/views/{,*/}*.blade.php', GLOB_BRACE))
        ->flatMap(function (string $view) {
            preg_match_all('/class="([^"]*)"/', file_get_contents($view), $found);

            return collect($found[1])->flatMap(fn (string $classes) => preg_split('/\s+/', $classes));
        })
        ->reject(fn (string $class) => $class === '' || preg_match('/[{}@$]/', $class))
        ->reject(fn (string $class) => preg_match($ignored, $class))
        ->unique()
        ->values();

    expect($used->diff($defined)->values()->all())->toBe([]);
});
