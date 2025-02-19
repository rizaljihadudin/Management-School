<?php

namespace App\Filament\Resources\AdjacencyResource\Pages;

use App\Filament\Resources\AdjacencyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAdjacency extends EditRecord
{
    protected static string $resource = AdjacencyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
