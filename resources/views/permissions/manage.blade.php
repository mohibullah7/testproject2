@extends('index')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2">Manage Role Permissions</h5>
                    <a href="{{ route('permissions.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bx bx-arrow-back"></i> Back to Roles
                    </a>
                </div>
                <div class="card-body">
                    <!-- Role Selector -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="role_select" class="form-label">Select Role</label>
                            <select class="form-select" id="role_select">
                                <option value="">-- Select a role --</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ $selectedRole && $selectedRole->id == $role->id ? 'selected' : '' }}>
                                        {{ ucfirst($role->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Permissions Section -->
                    <div id="permissionsSection" style="{{ $selectedRole ? 'display: block;' : 'display: none;' }}">
                        <div class="alert alert-info">
                            <i class="bx bx-info-circle"></i>
                            Managing permissions for: <strong id="roleName">{{ $selectedRole ? ucfirst($selectedRole->name) : '' }}</strong>
                        </div>

                        <form id="permissionsForm">
                            @csrf
                            <input type="hidden" id="role_id" name="role_id" value="{{ $selectedRole ? $selectedRole->id : '' }}">
                            
                            <div class="row">
                                <!-- Group permissions by module -->
                                @php
                                    $permissionsByModule = [];
                                    foreach($permissions as $permission) {
                                        $module = explode('-', $permission->name)[0] ?? 'general';
                                        if (!isset($permissionsByModule[$module])) {
                                            $permissionsByModule[$module] = [];
                                        }
                                        $permissionsByModule[$module][] = $permission;
                                    }
                                @endphp

                                @foreach($permissionsByModule as $module => $modulePermissions)
                                <div class="col-md-6 mb-4">
                                    <div class="card">
                                        <div class="card-header bg-label-primary">
                                            <h6 class="mb-0 text-capitalize">{{ $module }} Permissions</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-check mb-2">
                                                <input type="checkbox" class="form-check-input select-all" data-module="{{ $module }}">
                                                <label class="form-check-label fw-bold">Select All {{ ucfirst($module) }}</label>
                                            </div>
                                            <hr>
                                            @foreach($modulePermissions as $permission)
                                            <div class="form-check mb-2">
                                                <input type="checkbox" 
                                                       class="form-check-input permission-checkbox" 
                                                       name="permissions[]" 
                                                       value="{{ $permission->name }}"
                                                       id="perm_{{ $permission->id }}"
                                                       data-module="{{ $module }}"
                                                       {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                    {{ $permission->name }}
                                                    <small class="text-muted d-block">{{ ucfirst(str_replace('-', ' ', $permission->name)) }}</small>
                                                </label>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <div class="row mt-3">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bx bx-save"></i> Save Permissions
                                    </button>
                                    <button type="button" class="btn btn-secondary" id="resetBtn">
                                        <i class="bx bx-reset"></i> Reset
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div id="noRoleSelected" style="{{ $selectedRole ? 'display: none;' : 'display: block;' }}" class="text-center py-5">
                        <i class="bx bx-shield bx-lg text-muted"></i>
                        <h5 class="mt-3">Select a role to manage permissions</h5>
                        <p class="text-muted">Choose a role from the dropdown above to view and assign permissions</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- Include Select2 CSS and JS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Toastr for notifications -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize Select2
    $('#role_select').select2({
        theme: 'bootstrap-5',
        placeholder: 'Select a role',
        allowClear: true
    });
    
    // Role selection change
    $('#role_select').on('change', function() {
        const roleId = $(this).val();
        if(roleId) {
            window.location.href = "{{ route('permissions.manage') }}?role=" + roleId;
        } else {
            $('#permissionsSection').hide();
            $('#noRoleSelected').show();
        }
    });
    
    // Select All functionality
    $('.select-all').on('change', function() {
        const module = $(this).data('module');
        const isChecked = $(this).is(':checked');
        $(`.permission-checkbox[data-module="${module}"]`).prop('checked', isChecked);
    });
    
    // Update Select All checkbox when individual permissions change
    $('.permission-checkbox').on('change', function() {
        const module = $(this).data('module');
        const totalCheckboxes = $(`.permission-checkbox[data-module="${module}"]`).length;
        const checkedCheckboxes = $(`.permission-checkbox[data-module="${module}"]:checked`).length;
        const selectAllCheckbox = $(`.select-all[data-module="${module}"]`);
        
        if(checkedCheckboxes === totalCheckboxes) {
            selectAllCheckbox.prop('checked', true);
        } else {
            selectAllCheckbox.prop('checked', false);
        }
    });
    
    // Submit permissions form
    $('#permissionsForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = $(this).serialize();
        const roleId = $('#role_id').val();
        const roleName = $('#roleName').text();
        
        $.ajax({
            url: "{{ route('permissions.update-permissions') }}",
            type: "POST",
            data: formData,
            success: function(response) {
                if(response.success) {
                    toastr.success(response.message);
                    
                    // Update the checkboxes to reflect saved state
                    if(response.permissions) {
                        $('.permission-checkbox').each(function() {
                            const permValue = $(this).val();
                            $(this).prop('checked', response.permissions.includes(permValue));
                        });
                    }
                }
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON?.message || 'Failed to update permissions');
            }
        });
    });
    
    // Reset button
    $('#resetBtn').on('click', function() {
        if(confirm('Reset all permission changes for this role?')) {
            location.reload();
        }
    });
    
    // Initialize select all checkboxes based on current selections
    $('.select-all').each(function() {
        const module = $(this).data('module');
        const totalCheckboxes = $(`.permission-checkbox[data-module="${module}"]`).length;
        const checkedCheckboxes = $(`.permission-checkbox[data-module="${module}"]:checked`).length;
        
        if(checkedCheckboxes === totalCheckboxes && totalCheckboxes > 0) {
            $(this).prop('checked', true);
        }
    });
});
</script>

<style>
    .select2-container--bootstrap-5 .select2-selection {
        min-height: 38px;
    }
    .permission-checkbox {
        cursor: pointer;
    }
    .form-check-label {
        cursor: pointer;
    }
    .card {
        transition: all 0.3s ease;
    }
    .card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .bg-label-primary {
        background-color: #e7e7ff;
        color: #696cff;
    }
    .select-all {
        cursor: pointer;
    }
    hr {
        margin: 0.5rem 0;
    }
    .form-check {
        margin-bottom: 0.5rem;
    }
</style>
@endpush
@endsection