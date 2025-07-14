# Division/Section/Unit Management Feature Development Guide

This document provides a step-by-step guide on how the Division/Section/Unit Management feature was developed in the Travel Order System.

## 1. Database Setup

### 1.1 Create DivSecUnits Table
```bash
php artisan make:migration create_div_sec_units_table
```

#### Table Structure
```php
public function up(): void
{
    Schema::create('div_sec_units', function (Blueprint $table) {
        $table->id();
        $table->string('name')->unique();
        $table->text('description')->nullable();
        $table->timestamps();
    });
}
```

## 2. Model Creation

### 2.1 Create DivSecUnit Model
```bash
php artisan make:model Models/DivSecUnit
```

#### Model Configuration
```php
namespace App\Models;

class DivSecUnit extends Model
{
    protected $fillable = [
        'name',
        'description'
    ];

    public function positions(): HasMany
    {
        return $this->hasMany(Position::class);
    }

    public function employees(): HasManyThrough
    {
        return $this->hasManyThrough(Employee::class, Position::class);
    }
}
```

## 3. Controller Development

### 3.1 Create DivSecUnitController
```bash
php artisan make:controller DivSecUnitController
```

#### Controller Methods
```php
namespace App\Http\Controllers;

class DivSecUnitController extends Controller
{
    // Display units list
    public function index(Request $request)
    {
        $query = DivSecUnit::query();

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        $units = $query->paginate(10);
        
        return view('div_sec_units.index', compact('units'));
    }

    // Store new unit
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:div_sec_units',
                'description' => 'nullable|string',
            ]);

            $unit = DivSecUnit::create($validated);

            return redirect()->route('div_sec_units.index')
                ->with('success', 'Unit created successfully');

        } catch (\Exception $e) {
            \Log::error('Unit creation failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create unit: ' . $e->getMessage());
        }
    }
}
```

## 4. View Development

### 4.1 Create DivSecUnits Index View
```bash
mkdir -p resources/views/div_sec_units
```

#### Index View Content
```php
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="mb-0">Division/Section/Units</h3>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createUnitModal">
                Add New Unit
            </button>
        </div>

        <div class="card-body">
            <form class="mb-3">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" placeholder="Search units...">
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
                        <th>Description</th>
                        <th>Positions</th>
                        <th>Employees</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($units as $unit)
                        <tr>
                            <td>{{ $unit->name }}</td>
                            <td>{{ $unit->description }}</td>
                            <td>{{ $unit->positions->count() }}</td>
                            <td>{{ $unit->employees->count() }}</td>
                            <td>
                                <button class="btn btn-sm btn-info" onclick="editUnit({{ $unit->id }})">
                                    Edit
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="deleteUnit({{ $unit->id }})">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $units->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<!-- Create Unit Modal -->
<div class="modal fade" id="createUnitModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Unit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('div_sec_units.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <!-- Form fields -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Unit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
```

### 4.2 Create Create Modal Partial
```bash
mkdir -p resources/views/div_sec_units/partials
```

#### Create Modal Content
```php
<div class="modal-body">
    <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>
        @error('name')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea id="description" class="form-control @error('description') is-invalid @enderror" name="description">{{ old('description') }}</textarea>
        @error('description')
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
    // DivSecUnit Management Routes
    Route::get('/div_sec_units', [DivSecUnitController::class, 'index'])->name('div_sec_units.index');
    Route::post('/div_sec_units', [DivSecUnitController::class, 'store'])->name('div_sec_units.store');
    Route::get('/div_sec_units/{unit}', [DivSecUnitController::class, 'edit'])->name('div_sec_units.edit');
    Route::put('/div_sec_units/{unit}', [DivSecUnitController::class, 'update'])->name('div_sec_units.update');
    Route::delete('/div_sec_units/{unit}', [DivSecUnitController::class, 'destroy'])->name('div_sec_units.destroy');
});
```

## 6. Testing

### 6.1 Unit Tests
```bash
php artisan make:test DivSecUnitManagementTest
```

#### Test Methods
```php
namespace Tests\Feature;

class DivSecUnitManagementTest extends TestCase
{
    public function test_unit_can_be_created()
    {
        $response = $this->post('/div_sec_units', [
            'name' => 'Development Department',
            'description' => 'Responsible for software development and maintenance',
        ]);
        
        $response->assertRedirect('/div_sec_units');
        $this->assertDatabaseHas('div_sec_units', [
            'name' => 'Development Department',
        ]);
    }
}
```

### 6.2 Feature Tests
```php
namespace Tests\Feature;

class DivSecUnitManagementFeatureTest extends TestCase
{
    public function test_unit_creation_form_displays_correctly()
    {
        $response = $this->get('/div_sec_units/create');
        
        $response->assertStatus(200)
            ->assertSee('Create New Unit')
            ->assertSee('Name')
            ->assertSee('Description');
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

## 8. Common Issues and Solutions

### 8.1 Unit Creation Fails
- Check if name is unique
- Verify input validation
- Check database constraints

### 8.2 Search Not Working
- Verify database indexes
- Check search query logic
- Ensure proper pagination

### 8.3 Statistics Not Updating
- Verify relationships
- Check database triggers
- Test with different data

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
- Use unique constraints
- Implement proper relationships
- Validate data consistency

## 10. Maintenance

1. Regular Backups
- Database
- Configuration files
- Logs

2. Monitoring
- Error logs
- Database performance
- Unit assignments

3. Updates
- Keep Laravel updated
- Update dependencies
- Test after updates
