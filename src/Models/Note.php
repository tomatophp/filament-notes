<?php

namespace TomatoPHP\FilamentNotes\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @method static Builder<static> visibleTo(int | string | null $userId)
 */
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
        'icon',
    ];

    protected $casts = [
        'is_pined' => 'boolean',
        'is_public' => 'boolean',
        'checklist' => 'json',
    ];

    public function user(): MorphTo
    {
        return $this->morphTo('user');
    }

    public function model(): MorphTo
    {
        return $this->morphTo('model');
    }

    public function noteMetas(): HasMany
    {
        return $this->hasMany(NoteMeta::class);
    }

    /**
     * Limit a query to the notes a user can see: public notes, their own notes,
     * and private notes shared with them through the user access action.
     */
    public function scopeVisibleTo(Builder $query, int|string|null $userId): Builder
    {
        return $query->where(function (Builder $query) use ($userId): void {
            $query->where('is_public', true);

            if ($userId === null) {
                return;
            }

            $query
                ->orWhere('user_id', $userId)
                ->orWhereHas('noteMetas', fn (Builder $meta) => $meta
                    ->where('key', config('filament-notes.models.user'))
                    // Meta values are JSON cast, so a shared user id is stored as "5" or 5.
                    ->whereIn('value', [(string) $userId, json_encode((string) $userId)]));
        });
    }

    /**
     * Read a meta value, or write it when a value is given.
     *
     * @param  string|array<mixed>|object|null  $value
     * @return Model|string|array<mixed>|null
     */
    public function meta(string $key, string|array|object|null $value = null): Model|string|null|array
    {
        if ($value !== null) {
            return $this->noteMetas()->updateOrCreate(['key' => $key], ['value' => $value === 'null' ? null : $value]);
        }

        $meta = $this->noteMetas()->where('key', $key)->first();
        if ($meta) {
            return $meta->value;
        }

        return $this->noteMetas()->updateOrCreate(['key' => $key], ['value' => null]);
    }
}
