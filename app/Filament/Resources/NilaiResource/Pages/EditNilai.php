<?php

namespace App\Filament\Resources\NilaiResource\Pages;

use App\Filament\Resources\NilaiResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditNilai extends EditRecord
{
    protected static string $resource = NilaiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSaveFormAction(): Action
    {

        return Action::make('create')
            ->color(function(): string {
                return $this->data['nilai'] > 100 ? 'primary' : 'success';
            })
            ->disabled(function (): bool {
                return $this->data['nilai'] > 100 ? true : false ;
            })
            ->extraAttributes([
                'class' => $this->data['nilai'] > 100  ? 'cursor-not-allowed' : 'cursor-pointer',
            ]);
    }
}
