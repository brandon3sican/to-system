<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Position extends Model
{
    protected $fillable = [
        'name',
        'div_sec_unit_id'
    ];

    public function divSecUnit(): BelongsTo
    {
        return $this->belongsTo(DivSecUnit::class);
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }
}
