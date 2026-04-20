@extends('index')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-12 mb-4 order-0">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2">User Details</h5>
                    <div>
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-primary">
                            <i class="bx bx-edit"></i> Edit User
                        </a>
                        <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bx bx-arrow-back"></i> Back to Users
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Full Name</label>
                            <h5>{{ $user->name }}</h5>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Email Address</label>
                            <h5>{{ $user->email }}</h5>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Assigned Role</label>
                            <h5>
                                @foreach($user->roles as $role)
                                    <span class="badge bg-primary">{{ ucfirst($role->name) }}</span>
                                @endforeach
                            </h5>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Permissions</label>
                            <h5>
                                @foreach($user->permissions as $permission)
                                    <span class="badge bg-info">{{ ucfirst($permission->name) }}</span>
                                @endforeach
                            </h5>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Created At</label>
                            <h5>{{ $user->created_at->format('F d, Y H:i:s') }}</h5>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Last Updated</label>
                            <h5>{{ $user->updated_at->format('F d, Y H:i:s') }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection