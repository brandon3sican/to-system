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

## 11. API Documentation

### 11.1 Authentication Endpoints

#### Login
- **Endpoint**: POST `/api/auth/login`
- **Request Body**:
```json
{
    "username": "string",
    "password": "string",
    "remember": "boolean"
}
```
- **Response**:
```json
{
    "success": true,
    "data": {
        "token": "string",
        "user": {
            "id": "integer",
            "username": "string",
            "role": {
                "id": "integer",
                "name": "string"
            },
            "employee": {
                "id": "integer",
                "full_name": "string"
            }
        }
    }
}
```

#### Register
- **Endpoint**: POST `/api/auth/register`
- **Request Body**:
```json
{
    "username": "string",
    "password": "string",
    "password_confirmation": "string",
    "role_id": "integer",
    "employee_id": "integer"
}
```

#### Logout
- **Endpoint**: POST `/api/auth/logout`

#### Refresh Token
- **Endpoint**: POST `/api/auth/refresh`

### 11.2 User Management Endpoints

#### Get Users
- **Endpoint**: GET `/api/users`
- **Parameters**:
  - `search` (optional): Search term
  - `role_id` (optional): Filter by role
  - `employee_id` (optional): Filter by employee
  - `page` (optional): Pagination page
  - `per_page` (optional): Items per page

#### Create User
- **Endpoint**: POST `/api/users`
- **Request Body**:
```json
{
    "username": "string",
    "password": "string",
    "role_id": "integer",
    "employee_id": "integer"
}
```

#### Update User
- **Endpoint**: PUT `/api/users/{id}`
- **Request Body**:
```json
{
    "username": "string",
    "password": "string",
    "role_id": "integer",
    "employee_id": "integer"
}
```

#### Delete User
- **Endpoint**: DELETE `/api/users/{id}`

### 11.3 Response Formats

#### Success Response
```json
{
    "success": true,
    "data": {
        "id": "integer",
        "username": "string",
        "role": {
            "id": "integer",
            "name": "string"
        },
        "employee": {
            "id": "integer",
            "full_name": "string"
        },
        "last_login_at": "datetime",
        "last_login_ip": "string"
    }
}
```

#### Error Response
```json
{
    "success": false,
    "message": "Error message",
    "errors": {
        "field_name": ["error description"]
    },
    "status_code": 422
}
```

## 12. Model Implementation

### 12.1 User Model

```php
namespace App\Models;

class User extends Authenticatable
{
    use HasFactory, SoftDeletes, HasApiTokens;

    protected $fillable = [
        'username',
        'password',
        'role_id',
        'employee_id',
        'two_factor_secret',
        'two_factor_recovery_codes'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes'
    ];

    protected $casts = [
        'two_factor_recovery_codes' => 'json'
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function hasPermissionTo($permission): bool
    {
        return $this->role->hasPermissionTo($permission);
    }

    public function can($ability, $arguments = []): bool
    {
        return $this->role->can($ability, $arguments);
    }

    public function generateTwoFactorRecoveryCodes(): array
    {
        $codes = collect(range(1, 10))->map(function () {
            return Str::random(16);
        });

        $this->forceFill([
            'two_factor_recovery_codes' => json_encode($codes->toArray())
        ])->save();

        return $codes->toArray();
    }

    public function regenerateTwoFactorSecret(): string
    {
        $this->forceFill([
            'two_factor_secret' => $this->generateTwoFactorSecret()
        ])->save();

        return $this->two_factor_secret;
    }

    public function getTwoFactorQrCodeUrl(string $appName): string
    {
        return app(TwoFactorAuthenticatable::class)->getTwoFactorQrCodeUrl(
            $appName,
            $this->username,
            $this->two_factor_secret
        );
    }
}
```

### 12.2 Role Model

```php
namespace App\Models;

class Role extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'permissions'
    ];

    protected $casts = [
        'permissions' => 'json'
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permission');
    }

    public function hasPermissionTo($permission): bool
    {
        if (is_string($permission)) {
            return $this->permissions->contains('name', $permission);
        }

        return !!$permission->intersect($this->permissions)->count();
    }

    public function can($ability, $arguments = []): bool
    {
        return $this->hasPermissionTo($ability);
    }

    public function givePermissionTo($permissions): Role
    {
        $permissions = is_array($permissions) ? $permissions : func_get_args();

        $permissions = Permission::whereIn('name', $permissions)->get();

        if ($permissions->isEmpty()) {
            return $this;
        }

        $this->permissions()->saveMany($permissions);

        return $this;
    }

    public function revokePermissionTo($permissions): Role
    {
        $permissions = is_array($permissions) ? $permissions : func_get_args();

        $this->permissions()->detach(
            Permission::whereIn('name', $permissions)->get()
        );

        return $this;
    }
}
```

## 13. Controller Implementation

### 13.1 Authentication Controller

```php
namespace App\Http\Controllers\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'remember' => 'boolean'
        ]);

        if (!Auth::attempt(
            $request->only('username', 'password'),
            $request->boolean('remember')
        )) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip()
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'token' => $token,
                'user' => $user
            ]
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Successfully logged out'
        ]);
    }
}
```

### 13.2 User Management Controller

```php
namespace App\Http\Controllers;

class UserManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:administrator')->only([
            'store', 'update', 'destroy', 'updateRole'
        ]);
    }

    public function index(Request $request)
    {
        $query = User::query()
            ->with([
                'role',
                'employee' => function($q) {
                    $q->select('id', 'first_name', 'last_name');
                }
            ]);

        if ($request->filled('search')) {
            $query->where('username', 'like', "%{$request->search}%");
        }

        if ($request->filled('role_id')) {
            $query->where('role_id', $request->role_id);
        }

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        $users = $query->paginate($request->get('per_page', 10));
        
        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'employee_id' => 'required|exists:employees,id',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'username' => $validated['username'],
                'password' => Hash::make($validated['password']),
                'role_id' => $validated['role_id'],
                'employee_id' => $validated['employee_id']
            ]);

            // Generate two-factor authentication secret
            $user->regenerateTwoFactorSecret();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'data' => $user
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('User creation failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create user: ' . $e->getMessage()
            ], 422);
        }
    }

    public function updateRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        DB::beginTransaction();
        try {
            $user->update($validated);
            
            // Update permissions cache
            $user->forgetCachedPermissions();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'data' => $user
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }
}
```

## 14. View Components

### 14.1 Vue Component

```vue
<template>
  <div>
    <div class="card">
      <div class="card-header">
        <h3>Users</h3>
        <button @click="openCreateModal">Add User</button>
      </div>
      <div class="card-body">
        <div class="filters">
          <select v-model="filters.role_id">
            <option value="">All Roles</option>
            <option v-for="role in roles" :value="role.id">{{ role.name }}</option>
          </select>
          
          <select v-model="filters.employee_id">
            <option value="">All Employees</option>
            <option v-for="employee in employees" :value="employee.id">{{ employee.full_name }}</option>
          </select>
        </div>

        <div class="search-bar">
          <input v-model="search" placeholder="Search users...">
        </div>

        <div class="users-list">
          <user-card 
            v-for="user in users" 
            :key="user.id" 
            :user="user"
            @edit="editUser"
            @delete="deleteUser">
          </user-card>
        </div>

        <pagination 
          :total-pages="totalPages" 
          :current-page="currentPage"
          @page-changed="changePage">
        </pagination>
      </div>
    </div>

    <div v-if="showCreateModal" class="modal">
      <div class="modal-content">
        <div class="modal-header">
          <h4>{{ editingUser ? 'Edit User' : 'Create User' }}</h4>
          <button @click="closeModal">&times;</button>
        </div>
        <div class="modal-body">
          <form @submit.prevent="saveUser">
            <div class="form-group">
              <label>Username</label>
              <input v-model="form.username" required>
            </div>
            
            <div class="form-group" v-if="!editingUser">
              <label>Password</label>
              <input type="password" v-model="form.password" required>
            </div>
            
            <div class="form-group" v-if="!editingUser">
              <label>Confirm Password</label>
              <input type="password" v-model="form.password_confirmation" required>
            </div>
            
            <div class="form-group">
              <label>Role</label>
              <select v-model="form.role_id" required>
                <option value="">Select Role</option>
                <option v-for="role in roles" :value="role.id">{{ role.name }}</option>
              </select>
            </div>
            
            <div class="form-group">
              <label>Employee</label>
              <select v-model="form.employee_id" required>
                <option value="">Select Employee</option>
                <option v-for="employee in employees" :value="employee.id">{{ employee.full_name }}</option>
              </select>
            </div>
            
            <button type="submit">Save</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      users: [],
      roles: [],
      employees: [],
      search: '',
      showCreateModal: false,
      editingUser: null,
      filters: {
        role_id: null,
        employee_id: null
      },
      form: {
        username: '',
        password: '',
        password_confirmation: '',
        role_id: null,
        employee_id: null
      }
    };
  },
  methods: {
    async fetchUsers() {
      try {
        const response = await axios.get('/api/users', {
          params: {
            search: this.search,
            role_id: this.filters.role_id,
            employee_id: this.filters.employee_id
          }
        });
        this.users = response.data.data;
      } catch (error) {
        this.handleError(error);
      }
    },
    async saveUser() {
      try {
        const url = this.editingUser 
          ? `/api/users/${this.editingUser.id}`
          : '/api/users';
        
        const method = this.editingUser ? 'put' : 'post';
        
        await axios[method](url, this.form);
        this.closeModal();
        this.fetchUsers();
      } catch (error) {
        this.handleError(error);
      }
    }
  }
};
</script>
```

## 15. Testing

### 15.1 Unit Tests

```php
namespace Tests\Feature;

class UserManagementTest extends TestCase
{
    public function test_can_create_user()
    {
        $employee = Employee::factory()->create();
        $role = Role::factory()->create();
        
        $response = $this->actingAs($this->adminUser)
            ->postJson('/api/users', [
                'username' => 'testuser',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'role_id' => $role->id,
                'employee_id' => $employee->id
            ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', [
            'username' => 'testuser',
            'role_id' => $role->id,
            'employee_id' => $employee->id
        ]);
    }

    public function test_cannot_create_duplicate_username()
    {
        $employee = Employee::factory()->create();
        $role = Role::factory()->create();
        
        $this->actingAs($this->adminUser)
            ->postJson('/api/users', [
                'username' => 'testuser',
                'role_id' => $role->id,
                'employee_id' => $employee->id
            ]);

        $response = $this->actingAs($this->adminUser)
            ->postJson('/api/users', [
                'username' => 'testuser',
                'role_id' => $role->id,
                'employee_id' => $employee->id
            ]);

        $response->assertStatus(422);
    }

    public function test_can_update_user_role()
    {
        $user = User::factory()->create();
        $newRole = Role::factory()->create();
        
        $response = $this->actingAs($this->adminUser)
            ->putJson(
                '/api/users/' . $user->id . '/role',
                [
                    'role_id' => $newRole->id
                ]
            );

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'role_id' => $newRole->id
        ]);
    }
}
```

### 15.2 Feature Tests

```php
namespace Tests\Feature;

class AuthenticationTest extends TestCase
{
    public function test_can_login_with_valid_credentials()
    {
        $user = User::factory()->create();
        
        $response = $this->post('/api/auth/login', [
            'username' => $user->username,
            'password' => 'password',
            'remember' => false
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'token',
                    'user'
                ]
            ]);
    }

    public function test_cannot_login_with_invalid_credentials()
    {
        $response = $this->post('/api/auth/login', [
            'username' => 'nonexistent',
            'password' => 'wrongpassword'
        ]);

        $response->assertStatus(401);
    }
}
```

## 16. Best Practices

### 16.1 Security
- Implement proper password hashing
- Use role-based access control
- Implement two-factor authentication
- Use secure token generation
- Validate all inputs
- Implement proper session management
- Use HTTPS for all authentication
- Implement rate limiting

### 16.2 Performance
- Use eager loading for relationships
- Implement proper indexing
- Use pagination for large datasets
- Cache frequently accessed data
- Optimize database queries
- Batch operations for bulk updates

### 16.3 Data Integrity
- Maintain proper referential integrity
- Handle orphaned records
- Validate relationships
- Implement proper error handling
- Use transactions for complex operations
- Maintain audit logs
- Validate password complexity

## 17. Error Handling

### 17.1 Common Errors

1. **Authentication Errors**
   - Invalid credentials
   - Account locked
   - Session expired
   - Token invalid

2. **Authorization Errors**
   - Insufficient permissions
   - Unauthorized access
   - Invalid role assignments

3. **Data Integrity Errors**
   - Duplicate username
   - Invalid relationships
   - Orphaned records
   - Invalid permissions

### 17.2 Error Response Format

```json
{
    "success": false,
    "message": "Error message",
    "errors": {
        "field_name": ["error description"]
    },
    "status_code": 422
}
```

## 18. Maintenance

### 18.1 Regular Tasks
- Backup user data
- Clean up orphaned records
- Monitor performance
- Review audit logs
- Update documentation
- Validate relationships
- Test authentication flows
- Review security settings

### 18.2 Troubleshooting
- Check database constraints
- Review error logs
- Monitor API responses
- Validate relationships
- Test backup procedures
- Review authentication issues
- Check session management
