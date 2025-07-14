# User Management Feature Development Guide

This document provides a step-by-step guide on how the User Management feature was developed in the Travel Order System.

## 1. Database Setup

### 1.1 Create Users Table
```bash
php artisan make:migration create_users_table
```

#### Table Structure
```php
public function up(): void
{
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('username')->unique();
        $table->string('password');
        $table->foreignId('role_id')->constrained('roles');
        $table->foreignId('employee_id')->nullable()->constrained('employees')->onDelete('set null');
        $table->timestamps();
    });
}
```

### 1.2 Add Employee ID to Users Table
```bash
php artisan make:migration add_employee_id_to_users_table
```

#### Migration Content
```php
public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->unsignedBigInteger('employee_id')->nullable()->after('role_id');
        $table->foreign('employee_id')->references('id')->on('employees')->onDelete('set null');
    });
}
```

## 2. Model Creation

### 2.1 Create UserManagement Model
```bash
php artisan make:model Models/UserManagement
```

#### Model Configuration
```php
namespace App\Models;

class UserManagement extends Model
{
    protected $table = 'users';

    protected $fillable = [
        'username',
        'password',
        'role_id',
        'employee_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
```

## 3. Controller Development

### 3.1 Create UserManagementController
```bash
php artisan make:controller UserManagementController
```

#### Controller Methods
```php
namespace App\Http\Controllers;

class UserManagementController extends Controller
{
    // Display users list
    public function index(Request $request)
    {
        $query = UserManagement::with(['role', 'employee' => function($query) {
            $query->select('id', 'first_name', 'last_name');
        }])->whereHas('employee');
        
        $users = $query->paginate(10);
        $roles = Role::all();
        $employees = Employee::all();
        
        return view('users.index', compact('users', 'roles', 'employees'));
    }

    // Create new user
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'username' => 'required|string|max:255|unique:users',
                'password' => 'required|string|min:8',
                'employee_id' => 'required|exists:employees,id',
                'role_id' => 'required|exists:roles,id',
            ]);

            $user = UserManagement::create([
                'username' => $validated['username'],
                'password' => Hash::make($validated['password']),
                'employee_id' => $validated['employee_id'],
                'role_id' => $validated['role_id'],
            ]);

            return redirect()->route('users.index')
                ->with('success', 'User created successfully');

        } catch (\Exception $e) {
            \Log::error('User creation failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create user: ' . $e->getMessage());
        }
    }
}
```

## 4. View Development

### 4.1 Create Users Index View
```bash
mkdir -p resources/views/users
```

#### Index View Content
```php
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="mb-0">Users</h3>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createUserModal">
                Add New User
            </button>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <table class="table">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Employee</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->role->name }}</td>
                            <td>{{ $user->employee->full_name }}</td>
                            <td>
                                <button class="btn btn-sm btn-info" onclick="editUser({{ $user->id }})">
                                    Edit
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="deleteUser({{ $user->id }})">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create User Modal -->
<div class="modal fade" id="createUserModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <!-- Form fields -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create User</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
```

### 4.2 Create Create Modal Partial
```bash
mkdir -p resources/views/users/partials
```

#### Create Modal Content
```php
<div class="modal-body">
    <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required>
        @error('username')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
        @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="mb-3">
        <label for="employee_id" class="form-label">Employee</label>
        <select id="employee_id" class="form-control @error('employee_id') is-invalid @enderror" name="employee_id" required>
            <option value="">Select Employee</option>
            @foreach($employees as $employee)
                <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                    {{ $employee->full_name }} - {{ $employee->position->name }} ({{ $employee->employmentStatus->name }})
                </option>
            @endforeach
        </select>
        @error('employee_id')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="mb-3">
        <label for="role_id" class="form-label">Role</label>
        <select id="role_id" class="form-control @error('role_id') is-invalid @enderror" name="role_id" required>
            <option value="">Select Role</option>
            @foreach($roles as $role)
                <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                    {{ $role->name }}
                </option>
            @endforeach
        </select>
        @error('role_id')
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
    // User Management Routes
    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
    Route::get('/users/{user}', [UserManagementController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
});
```

## 6. Testing

### 6.1 Unit Tests
```bash
php artisan make:test UserManagementTest
```

#### Test Methods
```php
namespace Tests\Feature;

class UserManagementTest extends TestCase
{
    public function test_user_can_be_created()
    {
        $role = Role::factory()->create();
        $employee = Employee::factory()->create();
        
        $response = $this->post('/users', [
            'username' => 'testuser',
            'password' => 'password123',
            'employee_id' => $employee->id,
            'role_id' => $role->id,
        ]);
        
        $response->assertRedirect('/users');
        $this->assertDatabaseHas('users', [
            'username' => 'testuser',
            'role_id' => $role->id,
            'employee_id' => $employee->id,
        ]);
    }
}
```

### 6.2 Feature Tests
```php
namespace Tests\Feature;

class UserManagementFeatureTest extends TestCase
{
    public function test_user_creation_form_displays_correctly()
    {
        $response = $this->get('/users/create');
        
        $response->assertStatus(200)
            ->assertSee('Create New User')
            ->assertSee('Username')
            ->assertSee('Password')
            ->assertSee('Employee')
            ->assertSee('Role');
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

### 8.1 User Creation Fails
- Check if required roles exist in the roles table
- Verify employee exists and is active
- Ensure username is unique
- Check password meets minimum length requirements

### 8.2 Employee Dropdown Empty
- Run employee seeders:
```bash
php artisan db:seed --class=EmployeeSeeder
```

### 8.3 Role Dropdown Empty
- Run role seeders:
```bash
php artisan db:seed --class=RoleSeeder
```

## 9. Security Considerations

1. Password Hashing
- Use Laravel's built-in Hash facade
- Never store plain-text passwords
- Minimum password length: 8 characters

2. Input Validation
- Validate all form inputs
- Use Laravel's validation rules
- Sanitize user input

3. Authorization
- Use middleware to protect routes
- Implement role-based access control
- Never expose sensitive data

## 10. Maintenance

1. Regular Backups
- Database
- Configuration files
- Logs

2. Monitoring
- Error logs
- Database performance
- User activity

3. Updates
- Keep Laravel updated
- Update dependencies
- Test after updates
