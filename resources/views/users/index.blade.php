@extends('layouts.app')

@section('title', 'User Management')

@section('content')
    <div class="dashboard-content">
        <div class="dashboard-row">
            <div class="dashboard-col" style="flex: 3;">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3>Users</h3>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                                <i class="fas fa-plus me-1"></i>
                                Add User
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Search users...">
                                        <button class="btn btn-outline-secondary" type="button">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-select">
                                        <option value="">All Roles</option>
                                        <option value="admin">Administrator</option>
                                        <option value="user">User</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-select">
                                        <option value="">All Status</option>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Full Name</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td>{{ $user->full_name }}</td>
                                            <td>{{ $user->username }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->role }}</td>
                                            <td>
                                                <span class="badge bg-{{ $user->status_class }}">
                                                    {{ $user->status_text }}
                                                </span>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-info me-1" data-bs-toggle="modal" data-bs-target="#privilegesModal{{ $user->id }}">
                                                    <i class="fas fa-user-shield me-1"></i>
                                                    Privileges
                                                </button>
                                                <button class="btn btn-sm btn-primary me-1" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}">
                                                    <i class="fas fa-edit me-1"></i>
                                                    Edit
                                                </button>
                                                <button class="btn btn-sm btn-danger" onclick="confirmDelete('{{ $user->id }}')">
                                                    <i class="fas fa-trash me-1"></i>
                                                    Delete
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Privileges Modal -->
                                        <div class="modal fade" id="privilegesModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Manage Privileges - {{ $user->full_name }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('users.privileges.update', $user->id) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="form-check mb-2">
                                                                <input type="checkbox" class="form-check-input" id="privilege_travel_orders" name="privileges[]" value="travel_orders" {{ in_array('travel_orders', $user->privileges) ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="privilege_travel_orders">Manage Travel Orders</label>
                                                            </div>
                                                            <div class="form-check mb-2">
                                                                <input type="checkbox" class="form-check-input" id="privilege_users" name="privileges[]" value="users" {{ in_array('users', $user->privileges) ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="privilege_users">Manage Users</label>
                                                            </div>
                                                            <div class="form-check mb-2">
                                                                <input type="checkbox" class="form-check-input" id="privilege_printing" name="privileges[]" value="printing" {{ in_array('printing', $user->privileges) ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="privilege_printing">Manage Printing</label>
                                                            </div>
                                                            <div class="mt-3">
                                                                <button type="submit" class="btn btn-primary">Update Privileges</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Edit User Modal -->
                                        <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit User - {{ $user->full_name }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('users.update', $user->id) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="mb-3">
                                                                <label class="form-label">Full Name</label>
                                                                <input type="text" class="form-control" name="full_name" value="{{ $user->full_name }}" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Username</label>
                                                                <input type="text" class="form-control" name="username" value="{{ $user->username }}" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Email</label>
                                                                <input type="email" class="form-control" name="email" value="{{ $user->email }}" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Role</label>
                                                                <select class="form-select" name="role" required>
                                                                    <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                                                                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Administrator</option>
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Status</label>
                                                                <select class="form-select" name="status" required>
                                                                    <option value="active" {{ $user->status === 'active' ? 'selected' : '' }}>Active</option>
                                                                    <option value="inactive" {{ $user->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                                                </select>
                                                            </div>
                                                            <div class="mt-3">
                                                                <button type="submit" class="btn btn-primary">Update User</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" class="form-control" name="full_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" name="password_confirmation" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <select class="form-select" name="role" required>
                                <option value="user">User</option>
                                <option value="admin">Administrator</option>
                            </select>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">Add User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(userId) {
            if (confirm('Are you sure you want to delete this user?')) {
                window.location.href = '{{ route('users.destroy', '__id__') }}'.replace('__id__', userId);
            }
        }
    </script>
@endsection
