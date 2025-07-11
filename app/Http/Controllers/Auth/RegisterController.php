<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Employee;
use App\Models\Role;
use App\Models\DivSecUnit;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RegisterController extends Controller
{
    /**
     * Show the registration form.
     */
    public function showRegistrationForm()
    {
        $roles = Role::all();
        $divSecUnits = DivSecUnit::all();
        $positions = Position::all();

        return view('auth.register', compact('roles', 'divSecUnits', 'positions'));
    }

    /**
     * Handle a registration request.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:50', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role_id' => ['required', 'exists:roles,id'],
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string'],
            'birthdate' => ['required', 'date'],
            'gender' => ['required', Rule::in(['Male', 'Female', 'Other'])],
            'date_hired' => ['required', 'date'],
            'div_sec_unit_id' => ['required', 'exists:div_sec_units,id'],
            'position_id' => ['required', 'exists:positions,id'],
        ]);

        // Create user
        $user = User::create([
            'username' => $validated['username'],
            'password' => bcrypt($validated['password']),
            'role_id' => $validated['role_id'],
        ]);

        // Create employee
        Employee::create([
            'user_id' => $user->id,
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'birthdate' => $validated['birthdate'],
            'gender' => $validated['gender'],
            'date_hired' => $validated['date_hired'],
            'div_sec_unit_id' => $validated['div_sec_unit_id'],
            'position_id' => $validated['position_id'],
        ]);

        // Log the user in
        auth()->login($user);

        return redirect()->route('dashboard')->with('success', 'Registration successful!');
    }
}
