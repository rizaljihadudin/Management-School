<?php

namespace App\Filament\Resources\NilaiResource\Pages;

use App\Filament\Resources\NilaiResource;
use App\Models\CategoryNilai;
use App\Models\Classroom;
use App\Models\Nilai;
use App\Models\Periode;
use App\Models\Student;
use App\Models\Subject;
use Filament\Actions;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateNilai extends CreateRecord
{
    protected static string $resource = NilaiResource::class;

    protected static string $view = 'filament.resources.nilai-resource.pages.form-nilai';


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Card::make()
                            ->schema([
                                Select::make('classrooms')
                                    ->options(Classroom::all()->pluck('name', 'id'))
                                    ->label('Class')
                                    ->live()
                                    ->required()
                                    ->searchable()
                                    ->afterStateUpdated( function(Set $set){
                                        $set('student', null);
                                        $set('periode', null);
                                    }),
                                Select::make('periode')
                                    ->options(Periode::all()->pluck('name', 'id'))
                                    ->label('Periode')
                                    ->required()
                                    ->searchable()
                                    ->live()
                                    ->preload()
                                    ->afterStateUpdated( fn(Set $set) =>
                                        $set('student', null),
                                    ),
                                Select::make('subject_id')
                                    ->options(Subject::all()->pluck('name', 'id'))
                                    ->label('Subject')
                                    ->required()
                                    ->searchable(),
                                Select::make('category_nilai')
                                    ->options(CategoryNilai::all()->pluck('name', 'id'))
                                    ->label('Category Nilai')
                                    ->required()
                                    ->searchable()
                                    ->columnSpan(3),
                            ])->columns(3),

                            Repeater::make('nilaiStudents')
                                ->label('Grade')
                                ->schema(fn (Get $get): array => [
                                    Select::make('student')
                                        ->options(function() use ($get) {
                                            $data = Student::whereIn('id', function ($query) use ($get) {
                                                $query->select('students_id')
                                                    ->from('student_has_classes')
                                                    ->where('classrooms_id', $get('classrooms'))
                                                    ->where('periode_id', $get('periode'))
                                                    ->where('is_open', 1)
                                                    ->pluck('students_id');
                                                })->pluck('name', 'id');
                                                return $data;
                                            })
                                        ->label('Student'),
                                    TextInput::make('nilai')
                                ])->columns(2),
                        ])
            ]);
    }

    public function save()
    {
        $get = $this->form->getState();

        $insert = [];
        foreach ($get['nilaiStudents'] as $item) {
            array_push($insert, [
                'class_id'          => $get['classrooms'],
                'student_id'        => $item['student'],
                'subject_id'        => $get['subject_id'],
                'category_nilai_id' => $get['category_nilai'],
                'periode_id'        => $get['periode'],
                'nilai'             => $item['nilai'],
                'teacher_id'        => Auth::user()->id
            ]);
        }

        Nilai::insert($insert);

        return redirect()->to('admin/nilais');
    }

}
