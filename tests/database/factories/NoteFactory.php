<?php

namespace TomatoPHP\FilamentNotes\Tests\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use TomatoPHP\FilamentNotes\Models\Note;
use TomatoPHP\FilamentNotes\Tests\Models\User;

/**
 * @extends Factory<Note>
 */
class NoteFactory extends Factory
{
    protected $model = Note::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'user_type' => User::class,
            'title' => $this->faker->sentence(3),
            'body' => '<p>'.$this->faker->sentence().'</p>',
            'background' => '#F4F39E',
            'border' => '#DEE184',
            'color' => '#47576B',
            'font_size' => '1em',
            'group' => 'todo',
            'status' => 'pending',
            'is_pined' => false,
            'is_public' => false,
        ];
    }

    public function pinned(): static
    {
        return $this->state(['is_pined' => true]);
    }

    public function public(): static
    {
        return $this->state(['is_public' => true]);
    }
}
