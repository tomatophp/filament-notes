<?php

namespace TomatoPHP\FilamentNotes\Filament\Resources;

use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use TomatoPHP\FilamentNotes\Filament\Forms\NoteForm;
use TomatoPHP\FilamentNotes\Filament\Resources\NoteResource\Pages\ManageNotes;
use TomatoPHP\FilamentNotes\FilamentNotesPlugin;
use TomatoPHP\FilamentNotes\Models\Note;
use UnitEnum;

class NoteResource extends Resource
{
    protected static ?string $model = Note::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-bookmark';

    protected static bool $isScopedToTenant = false;

    public static function getNavigationIcon(): string|BackedEnum|Htmlable|null
    {
        return FilamentNotesPlugin::get()->navigationIcon ?: 'heroicon-o-bookmark';
    }

    public static function getNavigationGroup(): string|UnitEnum|null
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

    public static function form(Schema $schema): Schema
    {
        return $schema->components(NoteForm::make());
    }

    public static function table(Table $table): Table
    {
        $plugin = FilamentNotesPlugin::get();

        $groups = [];
        if ($plugin->useGroups) {
            $groups[] = Group::make('group');
        }
        if ($plugin->useStatus) {
            $groups[] = Group::make('status');
        }

        return $table
            ->defaultSort('is_pined', 'desc')
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->visibleTo(auth()->id()))
            ->columns([
                TextColumn::make('group')
                    ->hidden(! $plugin->useGroups)
                    ->label(trans('filament-notes::messages.columns.group'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('status')
                    ->hidden(! $plugin->useStatus)
                    ->label(trans('filament-notes::messages.columns.status'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('title')
                    ->label(trans('filament-notes::messages.columns.title'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('body')
                    ->label(trans('filament-notes::messages.columns.body'))
                    ->searchable(),
                TextColumn::make('date')
                    ->label(trans('filament-notes::messages.columns.date'))
                    ->date()
                    ->sortable(),
                TextColumn::make('time')
                    ->label(trans('filament-notes::messages.columns.time'))
                    ->time()
                    ->sortable(),
                IconColumn::make('is_pined')
                    ->label(trans('filament-notes::messages.columns.is_pined'))
                    ->boolean(),
                IconColumn::make('is_public')
                    ->label(trans('filament-notes::messages.columns.is_public'))
                    ->boolean(),
            ])
            ->content(fn () => view('filament-notes::note-table'))
            ->groups($groups)
            ->filters([
                SelectFilter::make('group')
                    ->hidden(! $plugin->useGroups)
                    ->searchable()
                    ->label(trans('filament-notes::messages.columns.group'))
                    ->options(fn (): array => NoteForm::typeOptions('groups')),
                SelectFilter::make('status')
                    ->hidden(! $plugin->useStatus)
                    ->searchable()
                    ->label(trans('filament-notes::messages.columns.status'))
                    ->options(fn (): array => NoteForm::typeOptions('status')),
                TernaryFilter::make('is_public')
                    ->label(trans('filament-notes::messages.columns.is_public')),
                TernaryFilter::make('is_pined')
                    ->label(trans('filament-notes::messages.columns.is_pined')),
            ])
            ->paginationPageOptions([12, 24, 48])
            ->defaultPaginationPageOption(12)
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageNotes::route('/'),
        ];
    }
}
