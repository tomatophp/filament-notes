<?php
namespace TomatoPHP\FilamentNotes\Http\Controllers;

use Illuminate\Http\Request;
use TomatoPHP\FilamentNotes\Models\Note;

class NotesController
{
    public function index(Note $note,string $uuid, Request $request)
    {
        if($uuid !== $note->meta('share')){
            abort(404);
        }

        return view('filament-notes::index', ['note' => $note]);
    }
}
