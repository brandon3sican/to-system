<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Position;
use App\Models\DivSecUnit;
use App\Models\EmploymentStatus;
use App\Models\UserManagement;

class Employee extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'phone',
        'address',
        'birthdate',
        'gender',
        'date_hired',
        'position_id',
        'div_sec_unit_id',
        'employment_status_id',
        'salary'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'salary' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the employee record.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function divSecUnit()
    {
        return $this->belongsTo(DivSecUnit::class, 'div_sec_unit_id');
    }

    public function employmentStatus()
    {
        return $this->belongsTo(EmploymentStatus::class, 'employment_status_id');
    }

    public function userManagement()
    {
        return $this->hasOne(UserManagement::class);
    }

    /**
     * Get the employee's full position title.
     */
    public function getFullPositionAttribute()
    {
        return $this->position . ' - ' . $this->division;
    }

    /**
     * Get the employee's formatted salary.
     */
    public function getFormattedSalaryAttribute()
    {
        return '₱' . number_format($this->salary, 2);
    }

    /**
     * Get the employee's station.
     */
    public function getStationAttribute()
    {
        return $this->official_station;
    }

    public function scopeOrderByFullName($query)
    {
        return $query->orderBy('last_name')
                    ->orderBy('first_name')
                    ->orderBy('middle_name');
    }

    public function getFullNameAttribute()
    {
        return trim("{$this->first_name} {$this->middle_name} {$this->last_name}");
    }
}
