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

    public static function getNavigationGroup(): ?string
    {
        return trans('filament-notes::messages.group');
    }

    public static function getPluralLabel(): ?string
    {
        return trans('filament-notes::messages.title');
    }

    public static function getLabel(): ?string
    {
        return trans('filament-notes::messages.single');
    }

    public static function getNavigationLabel(): string
    {
        return trans('filament-notes::messages.title');
    }

    public static function form(Form $form): Form
    {
        return $form->schema(NoteForm::make());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('is_pined', 'desc')
            ->modifyQueryUsing(function ($query){
                $query->where('is_public', true)->orWhere('user_id', auth()->id());
            })
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(trans('filament-notes::messages.columns.title'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('body')
                    ->label(trans('filament-notes::messages.columns.body'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('date')
                    ->label(trans('filament-notes::messages.columns.date'))
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('time')
                    ->label(trans('filament-notes::messages.columns.time'))
                    ->time()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_pined')
                    ->label(trans('filament-notes::messages.columns.is_pined'))
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_public')
                    ->label(trans('filament-notes::messages.columns.is_public'))
                    ->boolean(),
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
