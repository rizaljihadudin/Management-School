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
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use stdClass;

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
                TextColumn::make('No')->state(
                    static function (HasTable $livewire, stdClass $rowLoop): string {
                        return (string) (
                            $rowLoop->iteration +
                            ($livewire->getTableRecordsPerPage() * (
                                $livewire->getTablePage() - 1
                            ))
                        );
                    }
                ),
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
                ImageColumn::make('profile'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'off'       => 'gray',
                        'move'      => 'warning',
                        'accept'    => 'success',
                        'grade'     => 'danger',
                }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    BulkAction::make('Accept')
                        ->icon('heroicon-m-check')
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            return $records->each->update(['status' => 'accept']);
                        }),
                    BulkAction::make('Off')
                        ->icon('heroicon-m-x-circle')
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            return $records->each->update(function ($record){
                                $id = $record->id;
                                Student::where('id', $id)->update(['status' => 'off']);
                            });
                        }),
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
            'index'     => Pages\ListStudents::route('/'),
            'create'    => Pages\CreateStudent::route('/create'),
            'edit'      => Pages\EditStudent::route('/{record}/edit'),
            'view'      => Pages\ViewStudent::route('/{record}')
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Profile Student')
                    ->schema([
                        TextEntry::make('nis')
                            ->label('NIS'),
                        TextEntry::make('name')
                            ->label('Full Name'),
                        TextEntry::make('gender')
                            ->label('Gender'),
                        TextEntry::make('birthday')
                            ->label('Date of Birth'),
                        TextEntry::make('religion')
                            ->label('Religion'),
                        ImageEntry::make('profile')
                            ->label('Photo Profile')
                            ->height(70)
                            ->circular()
                            ->columnSpan(2),
                    ])->columns(2),
            ]);
    }

    public static function getLabel(): ?string
    {
        $locale = app()->getLocale();
        return $locale == 'id' ? 'Siswa' : 'Students';
    }
}
