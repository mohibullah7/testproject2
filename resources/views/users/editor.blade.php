@extends('index')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-12 mb-4 order-0">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2">Edit User</h5>
                    <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bx bx-arrow-back"></i> Back to Users
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <!-- Name Field -->
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $user->name) }}" 
                                       placeholder="Enter full name"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email Field -->
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', $user->email) }}" 
                                       placeholder="Enter email address"
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Password Field (Optional) -->
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">New Password <span class="text-muted">(Optional)</span></label>
                                <div class="input-group">
                                    <input type="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password" 
                                           placeholder="Enter new password (leave blank to keep current)">
                                    <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password">
                                        <i class="bx bx-show"></i>
                                    </button>
                                </div>
                                <small class="text-muted">Leave blank to keep current password. Minimum 8 characters if changing.</small>
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Confirm Password Field -->
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                <div class="input-group">
                                    <input type="password" 
                                           class="form-control" 
                                           id="password_confirmation" 
                                           name="password_confirmation" 
                                           placeholder="Confirm new password">
                                    <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password_confirmation">
                                        <i class="bx bx-show"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Role Selection Field -->
                            <div class="col-md-12 mb-3">
                                <label for="roles" class="form-label">Assign Role <span class="text-danger">*</span></label>
                                <select class="form-select @error('roles') is-invalid @enderror" 
                                        id="roles" 
                                        name="roles" 
                                        required>
                                    <option value="">Select a role</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}" 
                                            {{ old('roles', isset($userRoles) && in_array($role->name, $userRoles) ? 'selected' : '') }}>
                                            {{ ucfirst($role->name) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('roles')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Current role: 
                                    @foreach($user->roles as $role)
                                        <span class="badge bg-label-primary">{{ ucfirst($role->name) }}</span>
                                    @endforeach
                                </small>
                            </div>

                            <!-- User Info Card -->
                            <div class="col-12 mb-3">
                                <div class="card bg-label-info">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar flex-shrink-0 me-3">
                                                <span class="avatar-initial rounded bg-info">
                                                    <i class="bx bx-user"></i>
                                                </span>
                                            </div>
                                            <div>
                                                <small class="text-muted d-block">User Information</small>
                                                <h6 class="mb-0">Created: {{ $user->created_at->format('M d, Y H:i') }} | Last Updated: {{ $user->updated_at->format('M d, Y H:i') }}</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-update"></i> Update User
                                </button>
                                <button type="reset" class="btn btn-outline-secondary">
                                    <i class="bx bx-reset"></i> Reset
                                </button>
                                <a href="{{ route('users.index') }}" class="btn btn-outline-danger">
                                    <i class="bx bx-x"></i> Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bx-show');
                icon.classList.add('bx-hide');
            } else {
                input.type = 'password';
                icon.classList.remove('bx-hide');
                icon.classList.add('bx-show');
            }
        });
    });
</script>
@endpush
@endsection