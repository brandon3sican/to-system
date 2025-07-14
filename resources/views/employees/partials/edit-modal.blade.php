<!-- Edit Employee Modal -->
<div class="modal fade" id="editEmployeeModal" tabindex="-1">
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
                        <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name', $employee->first_name) }}" required>
                        @error('first_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name', $employee->last_name) }}" required>
                        @error('last_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone', $employee->phone) }}">
                        @error('phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea id="address" class="form-control @error('address') is-invalid @enderror" name="address" rows="3">{{ old('address', $employee->address) }}</textarea>
                        @error('address')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="birthdate" class="form-label">Birthdate</label>
                        <input id="birthdate" type="date" class="form-control @error('birthdate') is-invalid @enderror" name="birthdate" value="{{ old('birthdate', $employee->birthdate) }}" required>
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
                            <option value="Male" {{ old('gender', $employee->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender', $employee->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                            <option value="Other" {{ old('gender', $employee->gender) == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('gender')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="date_hired" class="form-label">Date Hired</label>
                        <input id="date_hired" type="date" class="form-control @error('date_hired') is-invalid @enderror" name="date_hired" value="{{ old('date_hired', $employee->date_hired) }}" required>
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
                                <option value="{{ $position->id }}" {{ old('position_id', $employee->position_id) == $position->id ? 'selected' : '' }}>
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
                                <option value="{{ $divSecUnit->id }}" {{ old('div_sec_unit_id', $employee->div_sec_unit_id) == $divSecUnit->id ? 'selected' : '' }}>
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
                                <option value="{{ $employmentStatus->id }}" {{ old('employment_status_id', $employee->employment_status_id) == $employmentStatus->id ? 'selected' : '' }}>
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
                        <input id="salary" type="number" step="0.01" class="form-control @error('salary') is-invalid @enderror" name="salary" value="{{ old('salary', $employee->salary) }}" required>
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