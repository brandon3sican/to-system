@extends('layouts.app')

@include('layouts.styles')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">Employees <small class="text-muted">({{ $employees->total() }} total)</small></h3>
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createEmployeeModal">
                            <i class="fas fa-plus me-2"></i>Add New
                        </button>
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="window.location.reload()">
                            <i class="fas fa-sync me-2"></i>Refresh
                        </button>
                    </div>
                </div>

                <!-- Floating Action Button -->
                <div class="position-fixed bottom-0 end-0 m-4">
                    <button type="button" class="btn btn-primary btn-lg rounded-circle shadow-lg" data-bs-toggle="modal" data-bs-target="#createEmployeeModal">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>

                <!-- Pagination -->
                <div class="card-footer py-3 border-top-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            Showing {{ $employees->firstItem() }} to {{ $employees->lastItem() }} of {{ $employees->total() }} entries
                        </div>
                        <div>
                            {{ $employees->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Search and Filter Form -->
                    <div class="card mb-3">
                        <div class="card-body">
                            <form action="{{ route('employees.index') }}" method="GET" class="row g-3">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-search"></i>
                                        </span>
                                        <input type="text" class="form-control" name="search" 
                                               placeholder="Search by name..." 
                                               value="{{ request('search') }}">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-user-tag"></i>
                                        </span>
                                        <select class="form-control" name="role">
                                            <option value="">Filter by Role</option>
                                            @foreach($roles as $role)
                                                <option value="{{ $role->name }}" 
                                                        {{ request('role') == $role->name ? 'selected' : '' }}>
                                                    {{ $role->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-briefcase"></i>
                                        </span>
                                        <select class="form-control" name="position">
                                            <option value="">Filter by Position</option>
                                            @foreach($positions as $position)
                                                <option value="{{ $position->name }}" 
                                                        {{ request('position') == $position->name ? 'selected' : '' }}>
                                                    {{ $position->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-filter me-2"></i>Filter
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Full Name</th>
                                    <th>Gender</th>
                                    <th>Date Hired</th>
                                    <th>Position</th>
                                    <th>Division/Section/Unit</th>
                                    <th>Employment Status</th>
                                    <th class="text-center" style="width: 150px">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($employees as $employee)
                                <tr>
                                    <td class="align-middle">
                                        {{ $employee->first_name }} {{ $employee->middle_name }} {{ $employee->last_name }}
                                    </td>
                                    <td class="align-middle">{{ $employee->gender }}</td>
                                    <td class="align-middle">{{ $employee->date_hired }}</td>
                                    <td class="align-middle">{{ $employee->position->name }}</td>
                                    <td class="align-middle">{{ $employee->divSecUnit->name }}</td>
                                    <td class="align-middle">{{ $employee->employmentStatus->name }}</td>
                                    <td>
                                        <div class="d-flex gap-2 justify-content-center">
                                            <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#viewEmployeeModal{{ $employee->id }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editEmployeeModal{{ $employee->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteEmployeeModal{{ $employee->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- View Modal -->
                                <div class="modal fade" id="viewEmployeeModal{{ $employee->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Employee Details</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p><strong>Full Name:</strong> {{ $employee->first_name }} {{ $employee->middle_name }} {{ $employee->last_name }}</p>
                                                        <p><strong>Gender:</strong> {{ $employee->gender }}</p>
                                                        <p><strong>Birthdate:</strong> {{ $employee->birthdate }}</p>
                                                        <p><strong>Phone:</strong> {{ $employee->phone }}</p>
                                                        <p><strong>Address:</strong> {{ $employee->address }}</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p><strong>Date Hired:</strong> {{ $employee->date_hired }}</p>
                                                        <p><strong>Position:</strong> {{ $employee->position->name }}</p>
                                                        <p><strong>Division/Section/Unit:</strong> {{ $employee->divSecUnit->name }}</p>
                                                        <p><strong>Employment Status:</strong> {{ $employee->employmentStatus->name }}</p>
                                                        <p><strong>Salary:</strong> ₱{{ number_format($employee->salary, 2) }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Edit Modal -->
                                <div class="modal fade" id="editEmployeeModal{{ $employee->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Employee</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('employees.update', $employee) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="first_name" class="form-label">First Name</label>
                                                        <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ $employee->first_name }}" required>
                                                        @error('first_name')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="middle_name" class="form-label">Middle Name</label>
                                                        <input id="middle_name" type="text" class="form-control @error('middle_name') is-invalid @enderror" name="middle_name" value="{{ $employee->middle_name }}">
                                                        @error('middle_name')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="last_name" class="form-label">Last Name</label>
                                                        <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ $employee->last_name }}" required>
                                                        @error('last_name')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="phone" class="form-label">Phone</label>
                                                        <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ $employee->phone }}">
                                                        @error('phone')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="address" class="form-label">Address</label>
                                                        <textarea id="address" class="form-control @error('address') is-invalid @enderror" name="address" rows="3">{{ $employee->address }}</textarea>
                                                        @error('address')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="birthdate" class="form-label">Birthdate</label>
                                                        <input id="birthdate" type="date" class="form-control @error('birthdate') is-invalid @enderror" name="birthdate" value="{{ $employee->birthdate }}" required>
                                                        @error('birthdate')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="gender" class="form-label">Gender</label>
                                                        <select id="gender" class="form-control @error('gender') is-invalid @enderror" name="gender" required>
                                                            <option value="">Select Gender</option>
                                                            <option value="Male" {{ $employee->gender == 'Male' ? 'selected' : '' }}>Male</option>
                                                            <option value="Female" {{ $employee->gender == 'Female' ? 'selected' : '' }}>Female</option>
                                                            <option value="Other" {{ $employee->gender == 'Other' ? 'selected' : '' }}>Other</option>
                                                        </select>
                                                        @error('gender')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="date_hired" class="form-label">Date Hired</label>
                                                        <input id="date_hired" type="date" class="form-control @error('date_hired') is-invalid @enderror" name="date_hired" value="{{ $employee->date_hired }}" required>
                                                        @error('date_hired')
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
                                                                <option value="{{ $position->id }}" {{ $employee->position_id == $position->id ? 'selected' : '' }}>
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
                                                            <option value="">Select Division/Section/Unit</option>
                                                            @foreach($divSecUnits as $divSecUnit)
                                                                <option value="{{ $divSecUnit->id }}" {{ $employee->div_sec_unit_id == $divSecUnit->id ? 'selected' : '' }}>
                                                                    {{ $divSecUnit->name }}
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
                                                            <option value="">Select Employment Status</option>
                                                            @foreach($employmentStatuses as $employmentStatus)
                                                                <option value="{{ $employmentStatus->id }}" {{ $employee->employment_status_id == $employmentStatus->id ? 'selected' : '' }}>
                                                                    {{ $employmentStatus->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('employment_status_id')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="salary" class="form-label">Salary</label>
                                                        <input id="salary" type="number" step="0.01" class="form-control @error('salary') is-invalid @enderror" name="salary" value="{{ $employee->salary }}" required>
                                                        @error('salary')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Update Employee</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Delete Modal -->
                                <div class="modal fade" id="deleteEmployeeModal{{ $employee->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Delete Employee</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('employees.destroy', $employee) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <div class="modal-body">
                                                    <p>Are you sure you want to delete this employee?</p>
                                                    <p class="text-danger">Warning: This action cannot be undone.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-danger">Delete Employee</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{ $employees->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Employee Modals -->
@include('employees.partials.create-modal', ['positions' => $positions, 'divSecUnits' => $divSecUnits, 'employmentStatuses' => $employmentStatuses])

@endsection
