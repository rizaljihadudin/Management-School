<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NilaiResource\Pages;
use App\Filament\Resources\NilaiResource\RelationManagers;
use App\Models\CategoryNilai;
use App\Models\Classroom;
use App\Models\Nilai;
use App\Models\Periode;
use App\Models\Student;
use App\Models\Subject;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NilaiResource extends Resource
{
    protected static ?string $model = Nilai::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([
                    Select::make('class_id')
                        ->options(Classroom::all()->pluck('name', 'id'))
                        ->label('Class')
                        ->searchable(),
                    Select::make('periode_id')
                        ->options(Periode::all()->pluck('name', 'id'))
                        ->label('Periode')
                        ->searchable(),
                    Select::make('subject_id')
                        ->options(Subject::all()->pluck('name', 'id'))
                        ->label('Subject')
                        ->searchable(),
                    Select::make('category_nilai_id')
                        ->options(CategoryNilai::all()->pluck('name', 'id'))
                        ->label('Category Nilai')
                        ->searchable(),
                    Select::make('student_id')
                        ->options(Student::all()->pluck('name', 'id'))
                        ->label('Student')
                        ->searchable(),
                    TextInput::make('nilai')
                ])->columns(3)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('student.name')
                    ->searchable(),
                TextColumn::make('subject.name')
                    ->searchable(),
                TextColumn::make('category_nilai.name')
                    ->searchable(),
                TextColumn::make('nilai'),
                TextColumn::make('periode.name')
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
            'index' => Pages\ListNilais::route('/'),
            'create' => Pages\CreateNilai::route('/create'),
            'edit' => Pages\EditNilai::route('/{record}/edit'),
        ];
    }

    public static function getLabel(): ?string
    {
        $locale = app()->getLocale();
        return $locale == 'id' ? 'Nilai' : 'Grades';
    }
}
