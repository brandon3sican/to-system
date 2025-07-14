# Employee Management Feature Development Guide

This document provides a step-by-step guide on how the Employee Management feature was developed in the Travel Order System.

## 1. Database Setup

### 1.1 Create Employees Table
```bash
php artisan make:migration create_employees_table
```

#### Table Structure
```php
public function up(): void
{
    Schema::create('employees', function (Blueprint $table) {
        $table->id();
        $table->string('first_name');
        $table->string('last_name');
        $table->string('phone')->nullable();
        $table->text('address')->nullable();
        $table->date('birthdate')->nullable();
        $table->enum('gender', ['Male', 'Female', 'Other'])->nullable();
        $table->date('date_hired')->nullable();
        $table->decimal('salary', 10, 2)->nullable();
        $table->foreignId('position_id')->constrained('positions');
        $table->foreignId('div_sec_unit_id')->constrained('div_sec_units');
        $table->foreignId('employment_status_id')->constrained('employment_statuses');
        $table->timestamps();

        // Unique constraint on first_name and last_name combination
        $table->unique(['first_name', 'last_name']);
    });
}
```

## 2. Model Creation

### 2.1 Create Employee Model
```bash
php artisan make:model Models/Employee
```

#### Model Configuration
```php
namespace App\Models;

class Employee extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'address',
        'birthdate',
        'gender',
        'date_hired',
        'salary',
        'position_id',
        'div_sec_unit_id',
        'employment_status_id'
    ];

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function divSecUnit(): BelongsTo
    {
        return $this->belongsTo(DivSecUnit::class);
    }

    public function employmentStatus(): BelongsTo
    {
        return $this->belongsTo(EmploymentStatus::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserManagement::class);
    }
}
```

## 3. Controller Development

### 3.1 Create EmployeeController
```bash
php artisan make:controller EmployeeController
```

#### Controller Methods
```php
namespace App\Http\Controllers;

class EmployeeController extends Controller
{
    // Display employees list
    public function index(Request $request)
    {
        $query = Employee::with([
            'position',
            'divSecUnit',
            'employmentStatus'
        ]);

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->search . '%')
                  ->orWhere('last_name', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%')
                  ->orWhere('address', 'like', '%' . $request->search . '%');
            });
        }

        $employees = $query->paginate(10);
        
        return view('employees.index', compact('employees'));
    }

    // Store new employee
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string',
                'birthdate' => 'nullable|date',
                'gender' => 'nullable|in:Male,Female,Other',
                'date_hired' => 'nullable|date',
                'salary' => 'nullable|numeric|min:0',
                'position_id' => 'required|exists:positions,id',
                'div_sec_unit_id' => 'required|exists:div_sec_units,id',
                'employment_status_id' => 'required|exists:employment_statuses,id',
            ]);

            $employee = Employee::create($validated);

            return redirect()->route('employees.index')
                ->with('success', 'Employee created successfully');

        } catch (\Exception $e) {
            \Log::error('Employee creation failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create employee: ' . $e->getMessage());
        }
    }
}
```

## 4. View Development

### 4.1 Create Employees Index View
```bash
mkdir -p resources/views/employees
```

#### Index View Content
```php
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="mb-0">Employees</h3>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createEmployeeModal">
                Add New Employee
            </button>
        </div>

        <div class="card-body">
            <form class="mb-3">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" placeholder="Search employees...">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Position</th>
                        <th>Unit</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($employees as $employee)
                        <tr>
                            <td>{{ $employee->full_name }}</td>
                            <td>{{ $employee->position->name }}</td>
                            <td>{{ $employee->divSecUnit->name }}</td>
                            <td>{{ $employee->employmentStatus->name }}</td>
                            <td>
                                <button class="btn btn-sm btn-info" onclick="editEmployee({{ $employee->id }})">
                                    Edit
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="deleteEmployee({{ $employee->id }})">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $employees->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<!-- Create Employee Modal -->
<div class="modal fade" id="createEmployeeModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Employee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('employees.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <!-- Form fields -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Employee</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
```

### 4.2 Create Create Modal Partial
```bash
mkdir -p resources/views/employees/partials
```

#### Create Modal Content
```php
<div class="modal-body">
    <div class="mb-3">
        <label for="first_name" class="form-label">First Name</label>
        <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" required>
        @error('first_name')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="mb-3">
        <label for="last_name" class="form-label">Last Name</label>
        <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" required>
        @error('last_name')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="mb-3">
        <label for="phone" class="form-label">Phone</label>
        <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}">
        @error('phone')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="mb-3">
        <label for="address" class="form-label">Address</label>
        <textarea id="address" class="form-control @error('address') is-invalid @enderror" name="address">{{ old('address') }}</textarea>
        @error('address')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="mb-3">
        <label for="birthdate" class="form-label">Birthdate</label>
        <input id="birthdate" type="date" class="form-control @error('birthdate') is-invalid @enderror" name="birthdate" value="{{ old('birthdate') }}">
        @error('birthdate')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="mb-3">
        <label for="gender" class="form-label">Gender</label>
        <select id="gender" class="form-control @error('gender') is-invalid @enderror" name="gender">
            <option value="">Select Gender</option>
            <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
            <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
            <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
        </select>
        @error('gender')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="mb-3">
        <label for="date_hired" class="form-label">Date Hired</label>
        <input id="date_hired" type="date" class="form-control @error('date_hired') is-invalid @enderror" name="date_hired" value="{{ old('date_hired') }}">
        @error('date_hired')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="mb-3">
        <label for="salary" class="form-label">Salary</label>
        <input id="salary" type="number" step="0.01" class="form-control @error('salary') is-invalid @enderror" name="salary" value="{{ old('salary') }}">
        @error('salary')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="mb-3">
        <label for="position_id" class="form-label">Position</label>
        <select id="position_id" class="form-control @error('position_id') is-invalid @enderror" name="position_id" required>
            <option value="">Select Position</option>
            @foreach($positions as $position)
                <option value="{{ $position->id }}" {{ old('position_id') == $position->id ? 'selected' : '' }}>
                    {{ $position->name }}
                </option>
            @endforeach
        </select>
        @error('position_id')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="mb-3">
        <label for="div_sec_unit_id" class="form-label">Division/Section/Unit</label>
        <select id="div_sec_unit_id" class="form-control @error('div_sec_unit_id') is-invalid @enderror" name="div_sec_unit_id" required>
            <option value="">Select Unit</option>
            @foreach($divSecUnits as $unit)
                <option value="{{ $unit->id }}" {{ old('div_sec_unit_id') == $unit->id ? 'selected' : '' }}>
                    {{ $unit->name }}
                </option>
            @endforeach
        </select>
        @error('div_sec_unit_id')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="mb-3">
        <label for="employment_status_id" class="form-label">Employment Status</label>
        <select id="employment_status_id" class="form-control @error('employment_status_id') is-invalid @enderror" name="employment_status_id" required>
            <option value="">Select Status</option>
            @foreach($employmentStatuses as $status)
                <option value="{{ $status->id }}" {{ old('employment_status_id') == $status->id ? 'selected' : '' }}>
                    {{ $status->name }}
                </option>
            @endforeach
        </select>
        @error('employment_status_id')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
```

## 5. Routes Configuration

### 5.1 Add Routes to web.php
```php
Route::middleware(['auth'])->group(function () {
    // Employee Management Routes
    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
    Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
    Route::get('/employees/{employee}', [EmployeeController::class, 'edit'])->name('employees.edit');
    Route::put('/employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
    Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
});
```

## 6. Testing

### 6.1 Unit Tests
```bash
php artisan make:test EmployeeManagementTest
```

#### Test Methods
```php
namespace Tests\Feature;

class EmployeeManagementTest extends TestCase
{
    public function test_employee_can_be_created()
    {
        $position = Position::factory()->create();
        $unit = DivSecUnit::factory()->create();
        $status = EmploymentStatus::factory()->create();
        
        $response = $this->post('/employees', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'position_id' => $position->id,
            'div_sec_unit_id' => $unit->id,
            'employment_status_id' => $status->id,
        ]);
        
        $response->assertRedirect('/employees');
        $this->assertDatabaseHas('employees', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'position_id' => $position->id,
            'div_sec_unit_id' => $unit->id,
            'employment_status_id' => $status->id,
        ]);
    }
}
```

### 6.2 Feature Tests
```php
namespace Tests\Feature;

class EmployeeManagementFeatureTest extends TestCase
{
    public function test_employee_creation_form_displays_correctly()
    {
        $response = $this->get('/employees/create');
        
        $response->assertStatus(200)
            ->assertSee('Create New Employee')
            ->assertSee('First Name')
            ->assertSee('Last Name')
            ->assertSee('Position')
            ->assertSee('Division/Section/Unit')
            ->assertSee('Employment Status');
    }
}
```

## 7. Deployment Checklist

1. Run database migrations:
```bash
php artisan migrate
```

2. Clear caches:
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

3. Clear compiled views:
```bash
php artisan optimize:clear
```

4. Verify environment variables:
- Database connection settings
- Application key
- Mail settings (if email notifications are used)

## 8. Common Issues and Solutions

### 8.1 Employee Creation Fails
- Check if required related records exist (position, unit, status)
- Verify unique constraint on first_name and last_name
- Check date formats
- Validate salary format

### 8.2 Dropdowns Empty
- Run seeders:
```bash
php artisan db:seed --class=PositionSeeder
php artisan db:seed --class=DivSecUnitSeeder
php artisan db:seed --class=EmploymentStatusSeeder
```

### 8.3 Search Not Working
- Verify database indexes
- Check search query logic
- Ensure proper pagination

## 9. Security Considerations

1. Input Validation
- Validate all form inputs
- Use Laravel's validation rules
- Sanitize user input

2. Authorization
- Use middleware to protect routes
- Implement role-based access control
- Never expose sensitive data

3. Data Integrity
- Use foreign key constraints
- Implement unique constraints
- Validate relationships

## 10. Maintenance

1. Regular Backups
- Database
- Configuration files
- Logs

2. Monitoring
- Error logs
- Database performance
- Employee activity

3. Updates
- Keep Laravel updated
- Update dependencies
- Test after updates
