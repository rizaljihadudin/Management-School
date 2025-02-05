<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Master Data';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([
                    TextInput::make('nis')
                    ->numeric()
                    ->length(12)
                    ->minLength(12)
                    ->maxLength(12)
                    ->label('NIS')
                    ->required(),
                    TextInput::make('name')
                        ->required(),
                    Select::make('gender')
                        ->required()
                        ->placeholder('-- Select Gender --')
                        ->options([
                            'Male' => 'Laki-laki',
                            'Female' => 'Perempuan',
                        ]),
                    DatePicker::make('birthday')
                        ->label('Date of Birth')
                        ->required()
                        ->native(false)
                        ->displayFormat('d/m/Y'),
                    Select::make('religion')
                        ->required()
                        ->placeholder('-- Select Religion --')
                        ->searchable()
                        ->options([
                            'Islam'     => 'Islam',
                            'Katolik'   => 'Katolik',
                            'Protestan' => 'Protestan',
                            'Hindu'     => 'Hindu',
                            'Buddha'    => 'Buddha',
                            'Khonghucu' => 'Khonghucu',
                        ]),
                    TextInput::make('contact')
                        ->numeric(true)
                        ->maxLength(13)
                        ->minLength(9),
                    FileUpload::make('profile')
                        ->label('Photo Profile')
                        ->directory('students')
                        ->columnSpan(2)
                ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nis')
                    ->label('NIS')
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Name Student')
                    ->searchable(),
                TextColumn::make('gender')
                    ->label('Gender')
                    ->searchable(),
                TextColumn::make('birthday')
                    ->label('Date of Birth'),
                TextColumn::make('religion')
                    ->label('Religion')
                    ->searchable(),
                ImageColumn::make('profile')
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
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }
}
