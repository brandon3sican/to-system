@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create New Position</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('positions.store') }}">
                        @csrf

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

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('positions.index') }}" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create Position</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
