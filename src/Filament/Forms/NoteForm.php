<?php

namespace TomatoPHP\FilamentNotes\Filament\Forms;

use TomatoPHP\FilamentIcons\Components\IconPicker;
use Filament\Forms;
use TomatoPHP\FilamentTypes\Models\Type;

class NoteForm
{
    public static function make(): array
    {
        return [
            Forms\Components\Grid::make([
                'sm' => 1,
                'lg' => 1
            ])->schema([
                Forms\Components\Tabs::make()
                    ->tabs([
                        Forms\Components\Tabs\Tab::make(trans('filament-notes::messages.tabs.general'))
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->label(trans('filament-notes::messages.columns.title'))
                                    ->columnSpanFull(),
                                Forms\Components\RichEditor::make('body')
                                    ->label(trans('filament-notes::messages.columns.body'))
                                    ->columnSpanFull(),
                                Forms\Components\DatePicker::make('date')->label(trans('filament-notes::messages.columns.date'))
                                     ->native(false),
                                Forms\Components\TimePicker::make('time')->label(trans('filament-notes::messages.columns.time'))
                                     ->native(false),
                                Forms\Components\Toggle::make('is_pined')
                                    ->label(trans('filament-notes::messages.columns.is_pined'))
                                    ->columnSpanFull(),
                                Forms\Components\Toggle::make('is_public')
                                    ->label(trans('filament-notes::messages.columns.is_public'))
                                    ->columnSpanFull(),
                            ])
                            ->columns([
                                'sm' => 1,
                                'lg' => 2
                            ]),
                        Forms\Components\Tabs\Tab::make(trans('filament-notes::messages.tabs.style'))
                            ->schema([
                                IconPicker::make('icon')
                                    ->label(trans('filament-notes::messages.columns.icon'))
                                    ->columnSpanFull(),
                                Forms\Components\ColorPicker::make('background')
                                    ->label(trans('filament-notes::messages.columns.background'))
                                    ->default('#F4F39E'),
                                Forms\Components\ColorPicker::make('border')
                                    ->label(trans('filament-notes::messages.columns.border'))
                                    ->default('#DEE184'),
                                Forms\Components\ColorPicker::make('color')
                                    ->label(trans('filament-notes::messages.columns.color'))
                                    ->default('#47576B'),
                                Forms\Components\Select::make('font_size')
                                    ->label(trans('filament-notes::messages.columns.font_size'))
                                    ->default("1em")
                                    ->searchable()
                                    ->options([
                                        '1em' => 'SM',
                                        '1.25em' => 'MD',
                                        '1.5em' => 'LG',
                                        '1.75em' => 'XL'
                                    ]),
                                Forms\Components\Select::make('group')
                                    ->hidden(!filament('filament-notes')->useGroups)
                                    ->label(trans('filament-notes::messages.columns.group'))
                                    ->searchable()
                                    ->options(
                                        Type::query()
                                            ->where('for', 'notes')
                                            ->where('type', 'groups')
                                            ->pluck('name', 'key')
                                            ->toArray()
                                    ),
                                Forms\Components\Select::make('status')
                                    ->hidden(!filament('filament-notes')->useStatus)
                                    ->label(trans('filament-notes::messages.columns.status'))
                                    ->searchable()
                                    ->options(
                                        Type::query()
                                            ->where('for', 'notes')
                                            ->where('type', 'status')
                                            ->pluck('name', 'key')
                                            ->toArray()
                                    ),
//                                Forms\Components\Select::make('font')
//                                    ->label(trans('filament-notes::messages.columns.font'))
//                                    ->searchable()
//                                    ->default('null')
//                                    ->options([
//                                        'null' => 'Default',
//                                        'Edu+AU+VIC+WA+NT+Hand' => 'Edu Australia VIC WA NT Hand',
//                                        'Playwrite+BE+VLG' => 'Playwrite België Vlaams Gewest',
//                                    ]),
                            ])
                            ->columns([
                                'sm' => 1,
                                'lg' => 3
                            ])

                    ])
                    ->contained(false)
                    ->columnSpanFull()
            ])
        ];
    }
}
