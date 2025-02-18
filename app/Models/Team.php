<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Filament\Models\Contracts\HasCurrentTenantLabel;
use Filament\Models\Contracts\HasName;

class Team extends Model implements HasCurrentTenantLabel, HasName
{
    protected $guarded = [];

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function getCurrentTenantLabel(): string
    {
        return 'Active School';
    }

    public function getFilamentName(): string
    {
        return "{$this->name} {$this->subscription_plan}";
    }
}
