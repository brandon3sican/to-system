<!-- Create Position Modal -->
<div class="modal fade" id="createPositionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Position</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('positions.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Position Name</label>
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>
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
                                <option value="{{ $divSecUnit->id }}" {{ old('div_sec_unit_id') == $divSecUnit->id ? 'selected' : '' }}>
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
                    <button type="submit" class="btn btn-primary">Create Position</button>
                </div>
            </form>
        </div>
    </div>
</div>
