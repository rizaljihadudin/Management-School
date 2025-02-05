<?php

namespace App\Filament\Resources\CategoryNilaiResource\Pages;

use App\Filament\Resources\CategoryNilaiResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Contracts\Support\Htmlable;

class ManageCategoryNilais extends ManageRecords
{
    protected static string $resource = CategoryNilaiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    #untuk title halaman
    public function getTitle(): string|Htmlable
    {
        return 'Category Nilai';
    }
}
