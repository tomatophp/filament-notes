<?php

namespace TomatoPHP\FilamentNotes\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Note extends Model
{
    protected $fillable = [
        'user_id',
        'user_type',
        'model_id',
        'model_type',
        'title',
        'group',
        'status',
        'body',
        'background',
        'checklist',
        'border',
        'color',
        'font_size',
        'font',
        'font_family',
        'date',
        'time',
        'order',
        'is_pined',
        'is_public',
        'place_in',
        'icon'
    ];

    protected $casts = [
        "is_pined" => "boolean",
        "is_public" => "boolean",
        "checklist" => "json",
    ];


    /**
     * @return MorphTo
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo('user');
    }

    /**
     * @return MorphTo
     */
    public function model(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo('model');
    }

}
