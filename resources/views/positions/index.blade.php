@extends('layouts.app')

@include('layouts.styles')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">Positions <small class="text-muted">({{ $positions->total() }} total)</small></h3>
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createPositionModal">
                            <i class="fas fa-plus me-2"></i>Add New
                        </button>
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="window.location.reload()">
                            <i class="fas fa-sync me-2"></i>Refresh
                        </button>
                    </div>
                </div>

                <!-- Floating Action Button -->
                <div class="position-fixed bottom-0 end-0 m-4">
                    <button type="button" class="btn btn-primary btn-lg rounded-circle shadow-lg" data-bs-toggle="modal" data-bs-target="#createPositionModal">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>

                <!-- Pagination -->
                <div class="card-footer py-3 border-top-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            Showing {{ $positions->firstItem() }} to {{ $positions->lastItem() }} of {{ $positions->total() }} entries
                        </div>
                        <div>
                            {{ $positions->links('pagination::bootstrap-5') }}
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
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Division/Section/Unit</th>
                                    <th>Created At</th>
                                    <th class="text-center" style="width: 150px">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($positions as $position)
                                <tr>
                                    <td class="align-middle">{{ $position->id }}</td>
                                    <td class="align-middle">{{ $position->name }}</td>
                                    <td class="align-middle">{{ $position->divSecUnit->name }}</td>
                                    <td class="align-middle">{{ $position->created_at->format('M d, Y H:i') }}</td>
                                    <td>
                                        <div class="d-flex gap-2 justify-content-center">
                                            <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editPositionModal{{ $position->id }}" title="Edit Position">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deletePositionModal{{ $position->id }}" title="Delete Position">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Edit Modal -->
                                <div class="modal fade" id="editPositionModal{{ $position->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Position</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('positions.update', $position) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="name" class="form-label">Position Name</label>
                                                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $position->name }}" required>
                                                        @error('name')
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
                                                                <option value="{{ $divSecUnit->id }}" {{ $position->div_sec_unit_id == $divSecUnit->id ? 'selected' : '' }}>
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
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Update Position</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Delete Modal -->
                                <div class="modal fade" id="deletePositionModal{{ $position->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Delete Position</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('positions.destroy', $position) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <div class="modal-body">
                                                    <p>Are you sure you want to delete this position?</p>
                                                    <p class="text-danger">Warning: This action cannot be undone.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-danger">Delete Position</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{ $positions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@include('positions.partials.create-modal')

@endsection
