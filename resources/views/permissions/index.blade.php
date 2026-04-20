@extends('index')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2">Roles & Permissions Management</h5>
                    <div>
                        @can('Add Role')
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addRoleModal">
                            <i class="bx bx-plus"></i> Add Role
                        </button>
                        @endcan

                        @can('Add Permission')
                        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addPermissionModal">
                            <i class="bx bx-plus"></i> Add Permission
                        </button>

                        @endcan

                        <a href="{{ route('permissions.manage') }}" class="btn btn-sm btn-info">
                            <i class="bx bx-cog"></i> Manage Permissions
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Roles Table -->
                    <h5 class="mb-3">Roles</h5>
                    <div class="table-responsive mb-5">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Role Name</th>
                                    <th>Permissions Count</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($roles as $role)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <span class="badge bg-label-primary">{{ ucfirst($role->name) }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-label-info">{{ $role->permissions->count() }} Permissions</span>
                                    </td>
                                    <td>{{ $role->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <a href="{{ route('permissions.manage', ['role' => $role->id]) }}" class="btn btn-sm btn-info">
                                            <i class="bx bx-edit"></i> Manage
                                        </a>
                                        @if($role->name != 'admin')
                                        <button class="btn btn-sm btn-danger delete-role" data-role-id="{{ $role->id }}" data-role-name="{{ $role->name }}">
                                            <i class="bx bx-trash"></i> Delete
                                        </button>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No roles found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Permissions Table -->
                    <h5 class="mb-3 mt-4">All Permissions</h5>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Permission Name</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($permissions as $permission)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <span class="badge bg-label-success">{{ $permission->name }}</span>
                                    </td>
                                    <td>{{ $permission->created_at->format('M d, Y') }}</td>
                                    {{-- <td>
                                        <button class="btn btn-sm btn-danger delete-permission" data-permission-id="{{ $permission->id }}" data-permission-name="{{ $permission->name }}">
                                            <i class="bx bx-trash"></i> Delete
                                        </button>
                                    </td> --}}
                                    @can('delete permissions')
                                        <td>
                                            <button class="btn btn-sm btn-danger delete-permission"
                                                data-permission-id="{{ $permission->id }}"
                                                data-permission-name="{{ $permission->name }}">
                                                <i class="bx bx-trash"></i> Delete
                                            </button>
                                        </td>
                                        @endcan

                                        {{-- @permission('delete permissions')
                                            <!-- button -->
                                        @endpermission --}}

                                        {{-- @role('admin')
                                            <!-- delete button -->
                                        @endrole --}}

                                        {{-- @if(auth()->user()->can('delete permissions'))
                                            <!-- button -->
                                        @endif --}}

                                        {{-- @canany(['delete permissions', 'edit permissions'])
                                            <!-- show actions -->
                                        @endcanany --}}

                                        {{-- <button class="btn btn-danger"
                                            @cannot('delete permissions') disabled @endcannot>
                                            Delete
                                        </button> --}}

                                        {{-- 
                                                                                <script>
                                            const canDelete = @json(auth()->user()->can('delete permissions'));
                                        </script> --}}

                                        {{-- ->middleware('permission:delete permissions') --}}

                                        {{-- $this->authorize('delete permissions'); --}}
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">No permissions found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Role Modal -->
<div class="modal fade" id="addRoleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addRoleForm">
                    @csrf
                    <div class="mb-3">
                        <label for="role_name" class="form-label">Role Name</label>
                        <input type="text" class="form-control" id="role_name" name="name" required placeholder="e.g., editor, viewer">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveRoleBtn">Save Role</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Permission Modal -->
<div class="modal fade" id="addPermissionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Permission</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addPermissionForm">
                    @csrf
                    <div class="mb-3">
                        <label for="permission_name" class="form-label">Permission Name</label>
                        <input type="text" class="form-control" id="permission_name" name="name" required placeholder="e.g., edit articles, delete users">
                        <small class="text-muted">Use format: action-resource (e.g., create-user, edit-post)</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="savePermissionBtn">Save Permission</button>
            </div>
        </div>
    </div>
</div>

@endsection


@push('styles')
<!-- Toastr CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<style>
    .toast-success {
        background-color: #28a745 !important;
    }
    .toast-error {
        background-color: #dc3545 !important;
    }
</style>
@endpush


@push('scripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    // Add Role
    $('#saveRoleBtn').click(function() {
        const formData = $('#addRoleForm').serialize();
        
        $.ajax({
            url: "{{ route('permissions.store-role') }}",
            type: "POST",
            data: formData,
            success: function(response) {
                if(response.success) {
                    toastr.success(response.message);
                    setTimeout(() => location.reload(), 1500);
                }
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON?.message || 'Failed to create role');
            }
        });
    });
    
    // Add Permission
    $('#savePermissionBtn').click(function() {
        const formData = $('#addPermissionForm').serialize();
        
        $.ajax({
            url: "{{ route('permissions.store-permission') }}",
            type: "POST",
            data: formData,
            success: function(response) {
                if(response.success) {
                    toastr.success(response.message);
                    setTimeout(() => location.reload(), 1500);
                }
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON?.message || 'Failed to create permission');
            }
        });
    });
    
    // Delete Role
    $('.delete-role').click(function() {
        const roleId = $(this).data('role-id');
        const roleName = $(this).data('role-name');
        
        if(confirm(`Are you sure you want to delete role "${roleName}"?`)) {
            $.ajax({
                url: `/permissions/roles/${roleId}`,
                type: "DELETE",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if(response.success) {
                        toastr.success(response.message);
                        setTimeout(() => location.reload(), 1500);
                    }
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON?.message || 'Failed to delete role');
                }
            });
        }
    });
    
    // Delete Permission
    $('.delete-permission').click(function() {
        const permissionId = $(this).data('permission-id');
        const permissionName = $(this).data('permission-name');
        
        if(confirm(`Are you sure you want to delete permission "${permissionName}"?`)) {
            $.ajax({
                url: `/permissions/permissions/${permissionId}`,
                type: "DELETE",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if(response.success) {
                        toastr.success(response.message);
                        setTimeout(() => location.reload(), 1500);
                    }
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON?.message || 'Failed to delete permission');
                }
            });
        }
    });
</script>
@endpush