<?php

namespace TomatoPHP\FilamentNotes\Filament\Resources;

use TomatoPHP\FilamentNotes\Filament\Forms\NoteForm;
use TomatoPHP\FilamentNotes\Filament\Resources\NoteResource\Pages;
use TomatoPHP\FilamentNotes\Filament\Resources\NoteResource\RelationManagers;
use TomatoPHP\FilamentNotes\Models\Note;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use TomatoPHP\FilamentIcons\Components\IconPicker;

class NoteResource extends Resource
{
    protected static ?string $model = Note::class;

    protected static ?string $navigationIcon = 'heroicon-o-bookmark';

    public static function form(Form $form): Form
    {
        return $form->schema(NoteForm::make());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('is_pined', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('body')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('time')
                    ->time()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_pined')
                    ->boolean(),
                Tables\Columns\TextColumn::make('order')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->content(fn()=> view('filament-notes::note-table'))
            ->filters([
                //
            ])
            ->paginationPageOptions(['12','24', '48'])
            ->defaultPaginationPageOption(12)
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageNotes::route('/'),
        ];
    }
}
