<?php

namespace TomatoPHP\FilamentNotes\Filament\Forms;

use TomatoPHP\FilamentIcons\Components\IconPicker;
use Filament\Forms;

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
                        Forms\Components\Tabs\Tab::make('General')
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->columnSpanFull(),
                                Forms\Components\RichEditor::make('body')
                                    ->columnSpanFull(),
                                Forms\Components\DatePicker::make('date'),
                                Forms\Components\TimePicker::make('time'),
                                Forms\Components\Toggle::make('is_pined')
                                    ->columnSpanFull(),
                                Forms\Components\Toggle::make('is_public')
                                    ->columnSpanFull(),
                            ])
                            ->columns([
                                'sm' => 1,
                                'lg' => 2
                            ]),
                        Forms\Components\Tabs\Tab::make('Style')
                            ->schema([
                                IconPicker::make('icon')->columnSpanFull(),
                                Forms\Components\ColorPicker::make('background')
                                    ->default('#F4F39E'),
                                Forms\Components\ColorPicker::make('border')
                                    ->default('#DEE184'),
                                Forms\Components\ColorPicker::make('color')
                                    ->default('#47576B'),
                                Forms\Components\Select::make('font_size')
                                    ->default("1em")
                                    ->searchable()
                                    ->options([
                                        '1em' => 'SM',
                                        '1.25em' => 'MD',
                                        '1.5em' => 'LG',
                                        '1.75em' => 'XL'
                                    ]),
//                                Forms\Components\Select::make('font')
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
