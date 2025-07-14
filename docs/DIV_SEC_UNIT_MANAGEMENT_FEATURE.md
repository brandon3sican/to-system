# Division/Section/Unit Management Feature Development Guide

This document provides a comprehensive guide on the Division/Section/Unit Management feature in the Travel Order System.

## 1. Feature Overview

The Division/Section/Unit Management feature allows administrators to manage organizational units within the system. Each unit can have multiple positions and employees associated with it.

### Key Features
- CRUD operations for units
- Unit hierarchy management
- Employee and position relationships
- Search and filtering capabilities
- Audit logging

## 2. Database Structure

### 2.1 Schema Design

```sql
CREATE TABLE div_sec_units (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE,
    description TEXT,
    parent_id BIGINT UNSIGNED NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (parent_id) REFERENCES div_sec_units(id)
);
```

### 2.2 Indexes
- Unique index on `name`
- Foreign key index on `parent_id`
- Composite index on `name, parent_id`

## 3. API Documentation

### 3.1 Endpoints

#### Get All Units
- **Endpoint**: GET `/api/div-sec-units`
- **Parameters**:
  - `search` (optional): Search term
  - `parent_id` (optional): Filter by parent unit
  - `page` (optional): Pagination page
  - `per_page` (optional): Items per page

#### Create Unit
- **Endpoint**: POST `/api/div-sec-units`
- **Request Body**:
```json
{
    "name": "string",
    "description": "string",
    "parent_id": "integer"
}
```

#### Update Unit
- **Endpoint**: PUT `/api/div-sec-units/{id}`
- **Request Body**:
```json
{
    "name": "string",
    "description": "string",
    "parent_id": "integer"
}
```

#### Delete Unit
- **Endpoint**: DELETE `/api/div-sec-units/{id}`

### 3.2 Response Formats

#### Success Response
```json
{
    "success": true,
    "data": {
        "id": "integer",
        "name": "string",
        "description": "string",
        "parent": {
            "id": "integer",
            "name": "string"
        },
        "positions_count": "integer",
        "employees_count": "integer"
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
    }
}
```

## 4. Model Implementation

### 4.1 Core Methods

```php
namespace App\Models;

class DivSecUnit extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'parent_id'
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function positions(): HasMany
    {
        return $this->hasMany(Position::class);
    }

    public function employees(): HasManyThrough
    {
        return $this->hasManyThrough(Employee::class, Position::class);
    }

    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        return $query;
    }
}
```

## 5. Controller Implementation

### 5.1 Authorization

```php
namespace App\Http\Controllers;

class DivSecUnitController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:administrator')->only(['store', 'update', 'destroy']);
    }

    public function index(Request $request)
    {
        $query = DivSecUnit::query()->withCount(['positions', 'employees']);

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('parent_id')) {
            $query->where('parent_id', $request->parent_id);
        }

        $units = $query->paginate($request->get('per_page', 10));
        
        return response()->json([
            'success' => true,
            'data' => $units
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:div_sec_units',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:div_sec_units,id',
        ]);

        $unit = DivSecUnit::create($validated);
        
        return response()->json([
            'success' => true,
            'data' => $unit
        ], 201);
    }
}
```

## 6. View Components

### 6.1 Vue Component

```vue
<template>
  <div>
    <div class="card">
      <div class="card-header">
        <h3>Division/Section/Units</h3>
        <button @click="openCreateModal">Add Unit</button>
      </div>
      <div class="card-body">
        <div class="search-bar">
          <input v-model="search" placeholder="Search units...">
        </div>
        <div class="unit-tree">
          <unit-tree :units="units" @edit="editUnit" @delete="deleteUnit"></unit-tree>
        </div>
      </div>
    </div>

    <div v-if="showCreateModal" class="modal">
      <div class="modal-content">
        <div class="modal-header">
          <h4>{{ editingUnit ? 'Edit Unit' : 'Create Unit' }}</h4>
          <button @click="closeModal">&times;</button>
        </div>
        <div class="modal-body">
          <form @submit.prevent="saveUnit">
            <input v-model="form.name" placeholder="Name">
            <textarea v-model="form.description" placeholder="Description"></textarea>
            <select v-model="form.parent_id">
              <option value="">No Parent</option>
              <option v-for="unit in parentUnits" :value="unit.id">{{ unit.name }}</option>
            </select>
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
      units: [],
      search: '',
      showCreateModal: false,
      editingUnit: null,
      form: {
        name: '',
        description: '',
        parent_id: null
      }
    };
  },
  computed: {
    parentUnits() {
      return this.units.filter(unit => !unit.parent_id);
    }
  },
  methods: {
    async fetchUnits() {
      try {
        const response = await axios.get('/api/div-sec-units', {
          params: { search: this.search }
        });
        this.units = response.data.data;
      } catch (error) {
        this.handleError(error);
      }
    },
    async saveUnit() {
      try {
        const url = this.editingUnit 
          ? `/api/div-sec-units/${this.editingUnit.id}`
          : '/api/div-sec-units';
        
        const method = this.editingUnit ? 'put' : 'post';
        
        await axios[method](url, this.form);
        this.closeModal();
        this.fetchUnits();
      } catch (error) {
        this.handleError(error);
      }
    }
  }
};
</script>
```

## 7. Testing

### 7.1 Unit Tests

```php
namespace Tests\Feature;

class DivSecUnitManagementTest extends TestCase
{
    public function test_can_create_div_sec_unit()
    {
        $response = $this->actingAs($this->adminUser)
            ->postJson('/api/div-sec-units', [
                'name' => 'Test Unit',
                'description' => 'Test Description'
            ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('div_sec_units', [
            'name' => 'Test Unit'
        ]);
    }

    public function test_cannot_create_duplicate_unit()
    {
        $this->actingAs($this->adminUser)
            ->postJson('/api/div-sec-units', [
                'name' => 'Test Unit'
            ]);

        $response = $this->actingAs($this->adminUser)
            ->postJson('/api/div-sec-units', [
                'name' => 'Test Unit'
            ]);

        $response->assertStatus(422);
    }
}
```

## 8. Best Practices

### 8.1 Security
- Use role-based access control
- Implement proper validation
- Use soft deletes for units
- Log all unit modifications
- Validate parent-child relationships

### 8.2 Performance
- Use eager loading for relationships
- Implement proper indexing
- Use pagination for large datasets
- Cache frequently accessed data
- Optimize database queries

### 8.3 Data Integrity
- Maintain proper referential integrity
- Handle orphaned records
- Validate hierarchical relationships
- Implement proper error handling
- Use transactions for complex operations

## 9. Error Handling

### 9.1 Common Errors

1. **Validation Errors**
   - Duplicate unit name
   - Invalid parent unit
   - Missing required fields

2. **Authorization Errors**
   - Insufficient permissions
   - Unauthorized access

3. **Data Integrity Errors**
   - Orphaned records
   - Invalid relationships
   - Circular references

### 9.2 Error Response Format

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

## 10. Maintenance

### 10.1 Regular Tasks
- Backup unit data
- Clean up orphaned records
- Monitor performance
- Review audit logs
- Update documentation

### 10.2 Troubleshooting
- Check database constraints
- Review error logs
- Monitor API responses
- Validate relationships
- Test backup procedures
