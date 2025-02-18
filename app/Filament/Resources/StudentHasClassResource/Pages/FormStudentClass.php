<?php

namespace App\Filament\Resources\StudentHasClassResource\Pages;

use App\Filament\Resources\StudentHasClassResource;
use App\Models\Classroom;
use App\Models\HomeRoom;
use App\Models\Periode;
use App\Models\Student;
use App\Models\StudentHasClass;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class FormStudentClass extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = StudentHasClassResource::class;

    protected static string $view = 'filament.resources.student-has-class-resource.pages.form-student-class';


    public $students = [];
    public $classrooms_id;
    public $periode;

    public function mount(): void
    {
        $this->form->fill();
    }

    public function getFormSchema(): array
    {
        return [
            Card::make([
                Select::make('students')
                    ->multiple()
                    ->searchable()
                    ->options(Student::filterByStudentHasClasses()->pluck('name', 'id'))
                    ->label('Student')
                    ->columnSpan(3),
                Select::make('classrooms_id')
                    ->searchable()
                    ->options(Classroom::all()->pluck('name', 'id'))
                    ->label('Class'),
                Select::make('periode')
                    ->label('Periode')
                    ->searchable()
                    ->options(Periode::all()->pluck('name', 'id'))
            ])->columns(3)
        ];
    }

    public function save()
    {
        $students = $this->students;
        $insert = [];
        foreach ($students as $student) {
            $insert[] = [
                'students_id'   => $student,
                'classrooms_id' => $this->classrooms_id,
                'periode_id'    => $this->periode,
                'is_open'       => 1
            ];
        }
        StudentHasClass::insert($insert);

        return redirect()->to('admin/student-has-classes');
    }

    public function getTitle(): string | Htmlable
    {
        return __('Form Student');
    }
}
