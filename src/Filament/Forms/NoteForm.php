<?php

namespace TomatoPHP\FilamentNotes\Filament\Forms;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use TomatoPHP\FilamentIcons\Components\IconPicker;
use TomatoPHP\FilamentNotes\FilamentNotesPlugin;
use TomatoPHP\FilamentTypes\Models\Type;

class NoteForm
{
    public static function make(): array
    {
        return [
            Grid::make([
                'sm' => 1,
                'lg' => 1,
            ])
                ->columnSpanFull()
                ->components([
                    Tabs::make()
                        ->tabs([
                            Tab::make(trans('filament-notes::messages.tabs.general'))
                                ->components([
                                    TextInput::make('title')
                                        ->label(trans('filament-notes::messages.columns.title'))
                                        ->columnSpanFull(),
                                    RichEditor::make('body')
                                        ->label(trans('filament-notes::messages.columns.body'))
                                        ->columnSpanFull(),
                                    DatePicker::make('date')
                                        ->label(trans('filament-notes::messages.columns.date'))
                                        ->native(false),
                                    TimePicker::make('time')
                                        ->label(trans('filament-notes::messages.columns.time'))
                                        ->native(false),
                                    Toggle::make('is_pined')
                                        ->label(trans('filament-notes::messages.columns.is_pined'))
                                        ->columnSpanFull(),
                                    Toggle::make('is_public')
                                        ->label(trans('filament-notes::messages.columns.is_public'))
                                        ->columnSpanFull(),
                                ])
                                ->columns([
                                    'sm' => 1,
                                    'lg' => 2,
                                ]),
                            Tab::make(trans('filament-notes::messages.tabs.style'))
                                ->components([
                                    IconPicker::make('icon')
                                        ->label(trans('filament-notes::messages.columns.icon'))
                                        ->columnSpanFull(),
                                    ColorPicker::make('background')
                                        ->label(trans('filament-notes::messages.columns.background'))
                                        ->default('#F4F39E'),
                                    ColorPicker::make('border')
                                        ->label(trans('filament-notes::messages.columns.border'))
                                        ->default('#DEE184'),
                                    ColorPicker::make('color')
                                        ->label(trans('filament-notes::messages.columns.color'))
                                        ->default('#47576B'),
                                    Select::make('font_size')
                                        ->label(trans('filament-notes::messages.columns.font_size'))
                                        ->default('1em')
                                        ->searchable()
                                        ->options([
                                            '1em' => 'SM',
                                            '1.25em' => 'MD',
                                            '1.5em' => 'LG',
                                            '1.75em' => 'XL',
                                        ]),
                                    Select::make('group')
                                        ->hidden(fn (): bool => ! FilamentNotesPlugin::get()->useGroups)
                                        ->label(trans('filament-notes::messages.columns.group'))
                                        ->searchable()
                                        ->options(fn (): array => static::typeOptions('groups')),
                                    Select::make('status')
                                        ->hidden(fn (): bool => ! FilamentNotesPlugin::get()->useStatus)
                                        ->label(trans('filament-notes::messages.columns.status'))
                                        ->searchable()
                                        ->options(fn (): array => static::typeOptions('status')),
                                ])
                                ->columns([
                                    'sm' => 1,
                                    'lg' => 3,
                                ]),
                        ])
                        ->contained(false)
                        ->columnSpanFull(),
                ]),
        ];
    }

    /**
     * Note groups / statuses stored in filament-types, keyed by type key with the translated name.
     *
     * @return array<string, string>
     */
    public static function typeOptions(string $type): array
    {
        return Type::query()
            ->where('for', 'notes')
            ->where('type', $type)
            ->get()
            ->mapWithKeys(fn (Type $record): array => [$record->key => (string) ($record->name ?: $record->key)])
            ->all();
    }
}
