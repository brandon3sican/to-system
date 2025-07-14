<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;

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

    protected $casts = [
        'password' => 'hashed',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Validate employee exists
            if (!$model->employee_id || !Employee::find($model->employee_id)) {
                throw ValidationException::withMessages([
                    'employee_id' => ['Employee not found.'],
                ]);
            }

            // Validate role exists
            if (!$model->role_id || !Role::find($model->role_id)) {
                throw ValidationException::withMessages([
                    'role_id' => ['Role not found.'],
                ]);
            }

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

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }
}
