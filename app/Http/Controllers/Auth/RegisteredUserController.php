<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Employee;
use App\Models\Position;
use App\Models\DivSecUnit;
use App\Models\EmploymentStatus;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

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
    public function create()
    {
        $roles = Role::all();
        $positions = Position::all();
        $divSecUnits = DivSecUnit::all();
        $employmentStatuses = EmploymentStatus::all();

        return view('auth.register', compact('roles', 'positions', 'divSecUnits', 'employmentStatuses'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Validate role first
            $role = Role::findOrFail($request->role_id);

            // Validate input based on role type
            $validationRules = [
                'role_id' => ['required', 'exists:roles,id'],
                'username' => ['required', 'string', 'max:50', 'unique:users'],
                'password' => ['required', 'confirmed', 'min:8'],
            ];

            // Add employee validation rules only if not admin
            if ($role->name !== 'Administrator') {
                $validationRules = array_merge($validationRules, [
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
            }

            $validated = $request->validate($validationRules);

            // Create employee if not admin
            $employee = null;
            if ($role->name !== 'Administrator') {
                $employee = Employee::create([
                    'first_name' => $validated['first_name'],
                    'last_name' => $validated['last_name'],
                    'phone' => $validated['phone'],
                    'address' => $validated['address'],
                    'birthdate' => $validated['birthdate'],
                    'gender' => $validated['gender'],
                    'date_hired' => $validated['date_hired'],
                    'position_id' => $validated['position_id'],
                    'div_sec_unit_id' => $validated['div_sec_unit_id'],
                    'employment_status_id' => $validated['employment_status_id'],
                    'salary' => $validated['salary'],
                ]);
            }

            // Create user
            $user = User::create([
                'username' => $validated['username'],
                'password' => Hash::make($validated['password']),
                'role_id' => $validated['role_id'],
                'employee_id' => $employee ? $employee->id : null,
            ]);

            DB::commit();

            Log::info('User registration successful', [
                'user_id' => $user->id,
                'role' => $role->name,
                'employee_id' => $employee ? $employee->id : null,
            ]);

            // Automatically log in the user
            Auth::login($user);
            
            return redirect()->route('dashboard')->with('success', __('Registration successful! Welcome to the dashboard.'));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Registration failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withInput()->with('error', __('Registration failed. Please try again.'));
        }
    }
}
