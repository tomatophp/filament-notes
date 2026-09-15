<?php

use Illuminate\Support\Facades\Route;
use TomatoPHP\FilamentNotes\Http\Controllers\NotesController;

Route::middleware('web')->group(function () {
    Route::get('notes/{note}/{uuid}', [NotesController::class, 'index'])
        ->name('notes.view');
});
