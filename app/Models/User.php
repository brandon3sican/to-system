<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'password',
        'role_id',
        'employee_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the employee record associated with the user.
     */
    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    /**
     * Get the user's full name.
     */
    /**
     * Get the user's full name.
     */
    public function getFullNameAttribute()
    {
        $middleInitial = $this->middle_name ? substr($this->middle_name, 0, 1) . '.' : '';
        return trim("{$this->first_name} {$middleInitial} {$this->last_name}");
    }

    /**
     * Get the user's last name first format.
     */
    public function getLastNameFirstAttribute()
    {
        $middleInitial = $this->middle_name ? substr($this->middle_name, 0, 1) . '.' : '';
        return trim("{$this->last_name}, {$this->first_name} {$middleInitial}");
    }

    /**
     * Get the user's role display name.
     */
    public function getRoleDisplayName()
    {
        $roles = [
            'employee' => 'Employee',
            'recommender' => 'Recommender',
            'approver' => 'Approver',
            'admin' => 'Administrator'
        ];
        return $roles[$this->role] ?? 'Employee';
    }

    /**
     * Check if the user has a specific role.
     */
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    /**
     * Check if the user is an admin.
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if the user is an approver.
     */
    public function isApprover()
    {
        return $this->role === 'approver';
    }

    /**
     * Check if the user is a recommender.
     */
    public function isRecommender()
    {
        return $this->role === 'recommender';
    }

    /**
     * Check if the user is an employee.
     */
    public function isEmployee()
    {
        return $this->role === 'employee';
    }
}
