<?php

namespace TomatoPHP\FilamentNotes\Http\Controllers;

use Illuminate\Contracts\View\View;
use TomatoPHP\FilamentNotes\Models\Note;

class NotesController
{
    public function index(Note $note, string $uuid): View
    {
        $share = $note->noteMetas()->where('key', 'share')->first()?->value;

        abort_if(blank($share) || ! hash_equals((string) $share, $uuid), 404);

        return view('filament-notes::index', ['note' => $note]);
    }
}
