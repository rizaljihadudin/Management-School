<?php

namespace App\Filament\Resources\StudentResource\Widgets;

use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Students', Student::count())
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart(
                    Student::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                        ->groupBy('date')
                        ->orderBy('date')
                        ->pluck('count')
                        ->toArray()
                )
                ->color('success')
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                    'wire:click' => "redirectTo('admin/students')",
                ]),
            Stat::make('Teacher', Teacher::count())
                //->descriptionIcon('heroicon-m-arrow-trending-up')
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                    'wire:click' => "redirectTo('admin/teacher')",
                ]),
            Stat::make('Subject', Subject::count())
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                    'wire:click' => "redirectTo('admin/subjects')",
                ]),
        ];
    }

    public function redirectTo($route)
    {
        return redirect()->to($route);
    }
}
