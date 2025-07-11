@extends('layouts.auth')

@section('title', 'Register')

@section('content')
<div class="auth-body">
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-4">
            <i class="fas fa-user-plus fa-2x text-primary"></i>
            <h2 class="mt-3">{{ __('Register') }}</h2>
            <p class="text-muted">{{ __('Create your account and employee profile') }}</p>
        </div>

        <!-- Account Information Section -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">{{ __('Account Information') }}</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="role_id" class="form-label">{{ __('Role') }}</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-user-tag"></i>
                        </span>
                        <select class="form-control @error('role_id') is-invalid @enderror" id="role_id" name="role_id" required>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('role_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- Account Details -->
                <div class="mb-3">
                    <label for="username" class="form-label">{{ __('Username') }}</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-user"></i>
                        </span>
                        <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username') }}" placeholder="{{ __('Choose a username') }}" required>
                    </div>
                    @error('username')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label">{{ __('Password') }}</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="{{ __('Choose a password') }}" required>
                    </div>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="{{ __('Confirm your password') }}" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Employee Information Section -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">{{ __('Employee Information') }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="first_name" class="form-label">{{ __('First Name') }}</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-user"></i>
                            </span>
                            <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{ old('first_name') }}" placeholder="{{ __('Enter your first name') }}" required>
                        </div>
                        @error('first_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="last_name" class="form-label">{{ __('Last Name') }}</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-user"></i>
                            </span>
                            <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" value="{{ old('last_name') }}" placeholder="{{ __('Enter your last name') }}" required>
                        </div>
                        @error('last_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label">{{ __('Phone') }}</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-phone"></i>
                            </span>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" placeholder="{{ __('Enter your phone number') }}" required>
                        </div>
                        @error('phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="birthdate" class="form-label">{{ __('Birthdate') }}</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-calendar"></i>
                            </span>
                            <input type="date" class="form-control @error('birthdate') is-invalid @enderror" id="birthdate" name="birthdate" value="{{ old('birthdate') }}" required>
                        </div>
                        @error('birthdate')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="gender" class="form-label">{{ __('Gender') }}</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-venus-mars"></i>
                            </span>
                            <select class="form-control @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                                <option value="Male">{{ __('Male') }}</option>
                                <option value="Female">{{ __('Female') }}</option>
                                <option value="Other">{{ __('Other') }}</option>
                            </select>
                        </div>
                        @error('gender')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="date_hired" class="form-label">{{ __('Date Hired') }}</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-calendar"></i>
                            </span>
                            <input type="date" class="form-control @error('date_hired') is-invalid @enderror" id="date_hired" name="date_hired" value="{{ old('date_hired') }}" required>
                        </div>
                        @error('date_hired')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="div_sec_unit_id" class="form-label">{{ __('Division/Section/Unit') }}</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-building"></i>
                            </span>
                            <select class="form-control @error('div_sec_unit_id') is-invalid @enderror" id="div_sec_unit_id" name="div_sec_unit_id" required>
                                @foreach($divSecUnits as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('div_sec_unit_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="position_id" class="form-label">{{ __('Position') }}</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-briefcase"></i>
                            </span>
                            <select class="form-control @error('position_id') is-invalid @enderror" id="position_id" name="position_id" required>
                                @foreach($positions as $position)
                                    <option value="{{ $position->id }}">{{ $position->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('position_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">{{ __('Address') }}</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-map-marker-alt"></i>
                        </span>
                        <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3" placeholder="{{ __('Enter your address') }}" required></textarea>
                    </div>
                    @error('address')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-user-plus me-2"></i>
                {{ __('Register') }}
            </button>
        </div>

        <!-- Login Link -->
        <div class="text-center mt-3">
            <a href="{{ route('login') }}" class="text-decoration-none">
                <i class="fas fa-sign-in-alt me-1"></i>
                {{ __('Already have an account?') }}
            </a>
        </div>
    </form>
</div>
@endsection
