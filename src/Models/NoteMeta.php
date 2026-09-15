<?php

namespace TomatoPHP\FilamentNotes\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function note(): BelongsTo
    {
        return $this->belongsTo(Note::class);
    }
}
