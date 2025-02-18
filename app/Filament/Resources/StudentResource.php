<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers;
use App\Filament\Resources\StudentResource\Widgets\StudentOverview;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
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

    protected static ?string $navigationGroup = 'Academic';

    protected static ?int $navigationSort = 2;


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
                    ->searchable()
                    ->formatStateUsing(function (string $state){
                        return ucwords($state);
                    }),
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
                    ->formatStateUsing(function (string $state){
                        return ucwords($state);
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'off'       => 'gray',
                        'move'      => 'warning',
                        'accept'    => 'success',
                        'grade'     => 'danger',

                }),
            ])
            ->defaultSort('name', 'asc')
            ->filters([

            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    BulkAction::make('Change Status')
                        ->icon('heroicon-m-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->form([
                            Select::make('status')
                                ->label('Status')
                                ->options([
                                    'accept'    => 'Accept',
                                    'off'       => 'Off',
                                    'move'      => 'Move',
                                    'grade'     => 'Grade',
                                ])
                                ->required()
                        ])
                        ->action(function (Collection $records, array $data) {
                            $records->each(function ($record) use ($data) {
                                $id = $record->id;
                                Student::where('id', $id)->update(['status' => $data['status']]);
                            });

                            Notification::make()
                                ->title('Notification')
                                ->success()
                                ->body('Changes status successfully')
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                    // BulkAction::make('Off')
                    //     ->icon('heroicon-m-x-circle')
                    //     ->requiresConfirmation()
                    //     ->action(function (Collection $records) {
                    //         return $records->each->update(function ($record){
                    //             $id = $record->id;
                    //             Student::where('id', $id)->update(['status' => 'off']);
                    //         });
                    //     }),
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
                Section::make()
                    ->schema([
                        Fieldset::make('Profile')
                            ->schema([
                                Split::make([
                                    ImageEntry::make('profile')
                                        ->hiddenLabel()
                                        ->grow(false),
                                    Grid::make(2)
                                        ->schema([
                                            Group::make([
                                                TextEntry::make('nis'),
                                                TextEntry::make('name'),
                                                TextEntry::make('gender'),
                                                TextEntry::make('birthday'),

                                            ])
                                            ->inlineLabel()
                                            ->columns(1),

                                            Group::make([
                                                TextEntry::make('religion'),
                                                TextEntry::make('contact'),
                                                TextEntry::make('status')
                                                ->badge()
                                                ->color(fn (string $state): string => match ($state) {
                                                    'accept' => 'success',
                                                    'off' => 'danger',
                                                    'grade' => 'success',
                                                    'move' => 'warning',
                                                    'wait' => 'gray'
                                                }),
                                                ViewEntry::make('QRCode')
                                                    ->view('filament.resources.students.qrcode'),
                                            ])
                                            ->inlineLabel()
                                            ->columns(1),
                                    ])

                                ])->from('lg')
                            ])->columns(1)
                    ])->columns(2)
            ]);
    }

    public static function getLabel(): ?string
    {
        $locale = app()->getLocale();
        return $locale == 'id' ? 'Siswa' : 'Students';
    }

}
