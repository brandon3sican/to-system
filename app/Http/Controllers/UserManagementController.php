<?php

namespace App\Http\Controllers;

use App\Models\UserManagement;
use App\Models\Role;
use App\Models\Position;
use App\Models\DivSecUnit;
use App\Models\EmploymentStatus;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = UserManagement::with(['role', 'employee'])
            ->paginate(10);
        $roles = Role::all();
        $employees = Employee::all();
        return view('users.index', compact('users', 'roles', 'employees'));
    }

    public function create()
    {
        $roles = Role::all();
        $employees = Employee::all();
        return view('users.index', compact('roles', 'employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'username' => 'required|unique:users|max:100',
            'password' => 'required|min:8',
            'role_id' => 'required|exists:roles,id',
        ]);

        UserManagement::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'employee_id' => $request->employee_id,
        ]);

        return redirect()->route('users.index')
            ->with('success', 'User created successfully.');
    }

    public function edit(UserManagement $user)
    {
        return response()->json([
            'user' => $user,
            'employee' => $user->employee,
            'roles' => Role::all(),
            'positions' => Position::all(),
            'divSecUnits' => DivSecUnit::all(),
            'employmentStatuses' => EmploymentStatus::all()
        ]);
    }

    public function update(Request $request, UserManagement $user)
    {
        // Update employee information first
        $request->validate([
            'first_name' => 'required|max:50',
            'last_name' => 'required|max:50',
        ]);

        $user->employee()->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
        ]);

        // Then update user information
        $request->validate([
            'username' => 'required|unique:users,username,' . $user->id . '|max:100',
            'password' => 'required|min:8',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user->update([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
        ]);

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(UserManagement $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }
}
