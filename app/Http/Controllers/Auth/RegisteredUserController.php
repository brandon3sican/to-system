<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Employee;
use App\Models\Position;
use App\Models\DivSecUnit;
use App\Models\EmploymentStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'role_id' => ['required', 'exists:roles,id'],
            'username' => ['required', 'string', 'max:50', 'unique:users'],
            'password' => ['required', 'confirmed', 'min:8'],
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string'],
            'birthdate' => ['required', 'date'],
            'gender' => ['required', Rule::in(['Male', 'Female', 'Other'])],
            'date_hired' => ['required', 'date'],
            'position_id' => ['required', 'exists:positions,id'],
            'div_sec_unit_id' => ['required', 'exists:div_sec_units,id'],
            'employment_status_id' => ['required', 'exists:employment_statuses,id'],
            'salary' => ['required', 'numeric', 'min:0'],
        ]);

        // Create employee first
        $employee = Employee::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'address' => $request->address,
            'birthdate' => $request->birthdate,
            'gender' => $request->gender,
            'date_hired' => $request->date_hired,
            'position_id' => $request->position_id,
            'div_sec_unit_id' => $request->div_sec_unit_id,
            'employment_status_id' => $request->employment_status_id,
            'salary' => $request->salary,
        ]);

        // Create user with employee reference
        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'employee_id' => $employee->id,
        ]);

        return redirect(RouteServiceProvider::HOME);
    }
}
