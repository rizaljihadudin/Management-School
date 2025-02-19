<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdjacencyResource\Pages;
use App\Filament\Resources\AdjacencyResource\RelationManagers;
use App\Models\Adjacency;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Saade\FilamentAdjacencyList\Forms\Components\AdjacencyList;

class AdjacencyResource extends Resource
{
    protected static ?string $model = Adjacency::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('name')
                            ->label('Name Menu')
                            ->required(),
                        AdjacencyList::make('subjects')
                            ->form([
                                Forms\Components\TextInput::make('label')
                                    ->required(),
                            ])
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAdjacencies::route('/'),
            'create' => Pages\CreateAdjacency::route('/create'),
            'edit' => Pages\EditAdjacency::route('/{record}/edit'),
        ];
    }
}
