<?php

namespace App\Filament\Resources\StudentHasClassResource\Pages;

use App\Filament\Resources\StudentHasClassResource;
use App\Models\Classroom;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListStudentHasClasses extends ListRecords
{
    protected static string $resource = StudentHasClassResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $data = [];
        $data['All'] = Tab::make();
        $classrooms = Classroom::orderBy('name')->get();
        foreach ($classrooms as $classroom) {
            $data[$classroom->name] = Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('classrooms_id', $classroom->id)
                ->where('is_open', true));
        }
        return $data;
    }

}
