<?php
use \Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function (){
    Route::get('notes/{note}/{uuid}', [\TomatoPHP\FilamentNotes\Http\Controllers\NotesController::class, 'index'])
        ->name('notes.view');
});
