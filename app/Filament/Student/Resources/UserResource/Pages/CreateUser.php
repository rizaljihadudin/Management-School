<?php

namespace App\Filament\Student\Resources\UserResource\Pages;

use App\Filament\Student\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
