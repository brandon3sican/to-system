@extends('layouts.app')

@include('layouts.styles')

@section('content')

@php
    $employees = $employees ?? Employee::all();
@endphp

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">Users <small class="text-muted">({{ $users->total() }} total)</small></h3>
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createUserModal">
                            <i class="fas fa-plus me-2"></i>Add New
                        </button>
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="window.location.reload()">
                            <i class="fas fa-sync me-2"></i>Refresh
                        </button>
                    </div>
                </div>

                <!-- Floating Action Button -->
                <div class="position-fixed bottom-0 end-0 m-4">
                    <button type="button" class="btn btn-primary btn-lg rounded-circle shadow-lg" data-bs-toggle="modal" data-bs-target="#createUserModal">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>

                <!-- Pagination -->
                <div class="card-footer py-3 border-top-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} entries
                        </div>
                        <div>
                            {{ $users->links('pagination::bootstrap-5') }}
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

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>Password</th>
                                    <th>Role</th>
                                    <th class="text-center" style="width: 150px">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td class="align-middle">
                                        {{ $user->employee ? ($user->employee->first_name . ' ' . $user->employee->last_name) : 'No Employee Assigned' }}
                                    </td>
                                    <td class="align-middle">{{ $user->username }}</td>
                                    <td class="align-middle">{{ $user->password }}</td>
                                    <td class="align-middle">{{ $user->role->name }}</td>
                                    <td>
                                        <div class="d-flex gap-2 justify-content-center">
                                            <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editUserModal" data-user-id="{{ $user->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteUserModal{{ $user->id }}" title="Delete User">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteUserModal{{ $user->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Delete User</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('users.destroy', $user) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to delete this user?</p>
                                                        <p class="text-danger">Warning: This action cannot be undone.</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-danger">Delete User</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@include('users.partials.create-modal', ['employees' => $employees])
@include('users.partials.edit-modal', ['employees' => $employees])

@endsection
