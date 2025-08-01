<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Position;
use App\Models\DivSecUnit;
use App\Models\EmploymentStatus;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::with(['position', 'divSecUnit', 'employmentStatus'])
            ->whereHas('position')
            ->whereHas('divSecUnit')
            ->whereHas('employmentStatus');

        // Search by name
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('middle_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhereHas('position', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by role
        if ($request->has('role')) {
            $role = $request->role;
            $query->whereHas('userManagement', function ($q) use ($role) {
                $q->whereHas('role', function ($q) use ($role) {
                    $q->where('name', 'like', "%{$role}%");
                });
            });
        }

        // Filter by position
        if ($request->has('position')) {
            $position = $request->position;
            $query->whereHas('position', function ($q) use ($position) {
                $q->where('name', 'like', "%{$position}%");
            });
        }

        $employees = $query->orderBy('last_name', 'asc')
            ->orderBy('first_name', 'asc')
            ->paginate(10)
            ->withQueryString();

        $positions = Position::all();
        $roles = Role::all();
        $divSecUnits = DivSecUnit::all();
        $employmentStatuses = EmploymentStatus::all();

        return view('employees.index', compact(
            'employees',
            'positions',
            'roles',
            'divSecUnits',
            'employmentStatuses'
        ));
    }

    public function create()
    {
        $positions = Position::all();
        $divSecUnits = DivSecUnit::all();
        $employmentStatuses = EmploymentStatus::all();

        return view('employees.create', compact(
            'positions',
            'divSecUnits',
            'employmentStatuses'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'birthdate' => 'required|date',
            'gender' => 'required|string|in:Male,Female,Other',
            'date_hired' => 'required|date',
            'position_id' => 'required|exists:positions,id',
            'div_sec_unit_id' => 'required|exists:div_sec_units,id',
            'employment_status_id' => 'required|exists:employment_statuses,id',
            'salary' => 'required|numeric|min:0',
        ]);

        Employee::create($validated);

        return redirect()->route('employees.index')
            ->with('success', 'Employee created successfully');
    }

    public function edit(Employee $employee)
    {
        $positions = Position::all();
        $divSecUnits = DivSecUnit::all();
        $employmentStatuses = EmploymentStatus::all();

        return view('employees.edit', compact(
            'employee',
            'positions',
            'divSecUnits',
            'employmentStatuses'
        ));
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'birthdate' => 'required|date',
            'gender' => 'required|string|in:Male,Female,Other',
            'date_hired' => 'required|date',
            'position_id' => 'required|exists:positions,id',
            'div_sec_unit_id' => 'required|exists:div_sec_units,id',
            'employment_status_id' => 'required|exists:employment_statuses,id',
            'salary' => 'required|numeric|min:0',
        ]);

        $employee->update($validated);

        return redirect()->route('employees.index')
            ->with('success', 'Employee updated successfully');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();

        return redirect()->route('employees.index')
            ->with('success', 'Employee deleted successfully');
    }
}