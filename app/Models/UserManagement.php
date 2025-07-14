<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;

class UserManagement extends Model
{
    protected $table = 'users';

    protected $fillable = [
        'username',
        'password',
        'role_id',
        'employee_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            Log::info('Attempting to create user:', [
                'username' => $model->username,
                'role_id' => $model->role_id,
                'employee_id' => $model->employee_id
            ]);
        });

        static::created(function ($model) {
            Log::info('User created successfully:', [
                'id' => $model->id,
                'username' => $model->username
            ]);
        });
    }
}
