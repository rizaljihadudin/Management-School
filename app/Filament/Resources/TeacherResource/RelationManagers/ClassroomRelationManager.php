<?php

namespace App\Filament\Resources\TeacherResource\RelationManagers;

use App\Models\Classroom;
use App\Models\Periode;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class ClassroomRelationManager extends RelationManager
{
    protected static string $relationship = 'classroom';

    protected static ?string $title = 'Home Room';


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('classrooms_id')
                    ->label('Select Class')
                    ->placeholder('-- Select Class --')
                    ->options(Classroom::all()->pluck('name', 'id'))
                    ->searchable()
                    ->relationship(name: 'classroom', titleAttribute: 'name')
                    ->createOptionForm([
                        TextInput::make('name')
                            ->reactive()
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
                            ->required(),
                        Hidden::make('slug'),
                    ])
                    ->createOptionAction(
                        fn (Action $action) =>
                            $action->modalWidth('2xl')
                                ->modalHeading('Add Classroom')
                                ->modalButton('Save Classroom'),
                    ),
                Select::make('periode_id')
                    ->label('Select Periode')
                    ->placeholder('-- Select Periode --')
                    ->options(Periode::all()->pluck('name', 'id'))
                    ->searchable()
                    ->relationship(name: 'periode', titleAttribute: 'name')
                    ->createOptionForm([
                        TextInput::make('name')
                            ->label('Periode')
                            ->required(),
                    ])
                    ->createOptionAction(
                        fn (Action $action) =>
                            $action->modalWidth('2xl')
                                ->modalHeading('Add Periode')
                                ->modalButton('Save Periode'),
                    ),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('classroom.name'),
                Tables\Columns\TextColumn::make('periode.name'),
                ToggleColumn::make('is_open'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->createAnother(false),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
