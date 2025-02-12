<?php

namespace App\Filament\Resources\ClassroomResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class SubjectsRelationManager extends RelationManager
{
    protected static string $relationship = 'subjects';
    protected static ?string $title = 'Subjects';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('kode')
                    ->required(),
                TextInput::make('name')
                    ->live(debounce:500)
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
                    ->required(),
                Hidden::make('slug'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
                Tables\Actions\AttachAction::make()
                    ->recordSelect(
                        fn (Select $select) => $select->placeholder('Select a Subject'),
                    )
                    ->attachAnother(false)
        ,
            ])
            ->actions([
                //Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make()
                    ->label('Remove'),
                //Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                    //Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                //Tables\Actions\CreateAction::make(),
                Tables\Actions\AttachAction::make()
                    ->recordSelect(
                        fn (Select $select) => $select->placeholder('Select a Subject'),
                    )
            ]);
    }

    public static function getLabel(): ?string
    {
        return 'Add New Subjects';
    }

}
