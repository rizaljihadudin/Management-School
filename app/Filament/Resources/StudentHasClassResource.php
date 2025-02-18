<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentHasClassResource\Pages;
use App\Filament\Resources\StudentHasClassResource\RelationManagers;
use App\Models\Classroom;
use App\Models\HomeRoom;
use App\Models\Periode;
use App\Models\Student;
use App\Models\StudentHasClass;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudentHasClassResource extends Resource
{
    protected static ?string $model = StudentHasClass::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Academic';

    protected static ?int $navigationSort = 3;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make([
                    Select::make('students_id')
                        ->searchable()
                        ->options(Student::all()->pluck('name', 'id'))
                        ->label('Student'),
                    Select::make('classrooms_id')
                        ->searchable()
                        ->options(Classroom::all()->pluck('name', 'id'))
                        ->label('Class'),
                    Select::make('periode_id')
                        ->label('Periode')
                        ->searchable()
                        ->options(Periode::all()->pluck('name', 'id'))
                ])->columns(3)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('students.name')
                    ->searchable(),
                TextColumn::make('classrooms.name')
                    ->searchable(),
                TextColumn::make('periode.name')
            ])
            ->filters([
                SelectFilter::make('classrooms_id')
                    ->label('Classrooms')
                    ->options(Classroom::all()->pluck('name', 'id'))
                    ->searchable()
                    ->multiple(),
                SelectFilter::make('periode_id')
                    ->label('Periode')
                    ->options(Periode::all()->pluck('name', 'id'))
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListStudentHasClasses::route('/'),
            'create' => Pages\FormStudentClass::route('/create'),
            'edit' => Pages\EditStudentHasClass::route('/{record}/edit'),
        ];
    }
}
