<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'salary',
        'position',
        'division',
        'official_station'
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
}
