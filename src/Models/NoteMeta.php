<?php

namespace TomatoPHP\FilamentNotes\Models;

use Illuminate\Database\Eloquent\Model;

class NoteMeta extends Model
{
    protected $fillable = [
        'model_id',
        'model_type',
        'note_id',
        'key',
        'value',
    ];

    protected $casts = [
        'value' => 'json',
    ];

    public function note()
    {
        return $this->belongsTo(Note::class);
    }
}
