<?php

namespace App\Http\Controllers;

use App\Models\UserManagement;
use App\Models\Role;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserManagementController extends Controller
{
    public function index()
    {
        $query = UserManagement::with(['role', 'employee' => function($query) {
            $query->select('id', 'first_name', 'last_name');
        }])->whereHas('employee');
        
        $users = $query->paginate(10);
        
        $roles = Role::all();
        $employees = Employee::all();
        
        return view('users.index', compact('users', 'roles', 'employees'));
    }

    public function create()
    {
        $roles = Role::all();
        $employees = Employee::all();
        
        // Return a JSON response with the data needed for the modal
        return response()->json([
            'roles' => $roles,
            'employees' => $employees
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'username' => 'required|string|max:255|unique:users',
                'password' => 'required|string|min:8',
                'employee_id' => 'required|exists:employees,id',
                'role_id' => 'required|exists:roles,id',
            ]);

            // Create the user
            $user = UserManagement::create([
                'username' => $validated['username'],
                'password' => Hash::make($validated['password']),
                'employee_id' => $validated['employee_id'],
                'role_id' => $validated['role_id'],
            ]);

            Log::info('User created successfully:', [
                'id' => $user->id,
                'username' => $user->username,
                'employee_id' => $user->employee_id,
                'role_id' => $user->role_id
            ]);

            return redirect()->route('users.index')
                ->with('success', 'User created successfully');

        } catch (\Exception $e) {
            Log::error('User creation failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create user: ' . $e->getMessage());
        }
    }

    public function edit(UserManagement $user)
    {
        return response()->json([
            'user' => $user,
            'employee' => $user->employee,
            'roles' => Role::all(),
            'positions' => null,
            'divSecUnits' => null,
            'employmentStatuses' => null
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
