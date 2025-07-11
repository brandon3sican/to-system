@extends('layouts.app')

@include('layouts.styles')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">Divisions/Sections/Units <small class="text-muted">({{ $divSecUnits->total() }} total)</small></h3>
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createDivSecUnitModal">
                            <i class="fas fa-plus me-2"></i>Add New
                        </button>
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="window.location.reload()">
                            <i class="fas fa-sync me-2"></i>Refresh
                        </button>
                    </div>
                </div>

                <!-- Floating Action Button -->
                <div class="position-fixed bottom-0 end-0 m-4">
                    <button type="button" class="btn btn-primary btn-lg rounded-circle shadow-lg" data-bs-toggle="modal" data-bs-target="#createDivSecUnitModal">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>

                <!-- Pagination -->
                <div class="card-footer py-3 border-top-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            Showing {{ $divSecUnits->firstItem() }} to {{ $divSecUnits->lastItem() }} of {{ $divSecUnits->total() }} entries
                        </div>
                        <div>
                            {{ $divSecUnits->links('pagination::bootstrap-5') }}
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
                                    <th>Description</th>
                                    <th>Created At</th>
                                    <th class="text-center" style="width: 150px">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($divSecUnits as $divSecUnit)
                                <tr>
                                    <td class="align-middle">{{ $divSecUnit->id }}</td>
                                    <td class="align-middle">{{ $divSecUnit->name }}</td>
                                    <td class="align-middle">{{ $divSecUnit->description }}</td>
                                    <td class="align-middle">{{ $divSecUnit->created_at->format('M d, Y H:i') }}</td>
                                    <td>
                                        <div class="d-flex gap-2 justify-content-center">
                                            <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editDivSecUnitModal{{ $divSecUnit->id }}" title="Edit Unit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteDivSecUnitModal{{ $divSecUnit->id }}" title="Delete Unit">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>

                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editDivSecUnitModal{{ $divSecUnit->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Division/Section/Unit</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('divsecunits.update', $divSecUnit) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="name" class="form-label">Name</label>
                                                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $divSecUnit->name }}" required>
                                                            @error('name')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="description" class="form-label">Description</label>
                                                            <textarea id="description" class="form-control @error('description') is-invalid @enderror" name="description" rows="3">{{ $divSecUnit->description }}</textarea>
                                                            @error('description')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary">Update Division/Section/Unit</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteDivSecUnitModal{{ $divSecUnit->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Delete Division/Section/Unit</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('divsecunits.destroy', $divSecUnit) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to delete this division/section/unit?</p>
                                                        <p class="text-danger">Warning: This action cannot be undone.</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-danger">Delete Division/Section/Unit</button>
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

                    {{ $divSecUnits->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@include('divsecunits.partials.create-modal')

@endsection
