<?php

use TomatoPHP\FilamentNotes\Console\FilamentNotesInstall;

use function Pest\Laravel\artisan;

it('runs the install command', function () {
    // The real command shells out to `php artisan migrate`; record the sub-process calls instead.
    $command = new class extends FilamentNotesInstall
    {
        public array $ran = [];

        public function artisanCommand(array $command, ?bool $withOutput = false): void
        {
            $this->ran[] = $command;
        }
    };

    app()->instance(FilamentNotesInstall::class, $command);

    artisan('filament-notes:install')
        ->expectsOutputToContain('Filament Notes installed successfully.')
        ->assertSuccessful();

    expect($command->ran)->toBe([['migrate'], ['optimize:clear']]);
});
