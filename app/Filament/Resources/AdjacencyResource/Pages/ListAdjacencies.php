<?php

namespace App\Filament\Resources\AdjacencyResource\Pages;

use App\Filament\Resources\AdjacencyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAdjacencies extends ListRecords
{
    protected static string $resource = AdjacencyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
