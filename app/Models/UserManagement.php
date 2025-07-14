<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserManagement extends Model
{
    protected $table = 'users';

    protected $fillable = [
        'name',
        'username',
        'password',
        'role_id',
        'employee_id'
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function getEmployeeFullNameAttribute()
    {
        if ($this->employee) {
            return $this->employee->first_name . ' ' . $this->employee->last_name;
        }
        return null;
    }
}
