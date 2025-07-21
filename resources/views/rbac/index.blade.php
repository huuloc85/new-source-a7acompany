@extends('layouts.'.$layout)

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h2 class="mb-0">
                    <i class="fas fa-users-cog me-2"></i>
                    Quản Lý Phân Quyền Đa Vai Trò
                </h2>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- Form chọn multiple roles --}}
                <div class="row mb-4">
                    <div class="col-md-8">
                        <div class="card border-primary h-100">
                            <div class="card-header bg-primary text-white">
                                <i class="fas fa-user-friends me-2"></i>
                                Chọn Vai Trò Quản Lý
                                <small class="ms-2">(Có thể chọn nhiều vai trò)</small>
                            </div>
                            <div class="card-body">
                                <form method="GET" action="{{ route('rbac.index') }}" id="roleForm">
                                    <div class="mb-3">
                                        <div class="role-selection-container">
                                            <div class="d-flex align-items-center mb-3">
                                                <button
                                                    type="button"
                                                    class="btn btn-outline-primary btn-sm me-2"
                                                    id="selectAllRoles">
                                                    <i class="fas fa-check-double me-1"></i>
                                                    Chọn tất cả
                                                </button>
                                                <button
                                                    type="button"
                                                    class="btn btn-outline-secondary btn-sm me-2"
                                                    id="deselectAllRoles">
                                                    <i class="fas fa-times me-1"></i>
                                                    Bỏ chọn tất cả
                                                </button>
                                                <span class="text-muted small">
                                                    <i class="fas fa-info-circle me-1"></i>
                                                    Tự động cập nhật khi thay đổi
                                                </span>
                                            </div>
                                            <div class="row">
                                                @foreach ($roles as $index => $role)
                                                    <div class="col-md-6 mb-2">
                                                        <div
                                                            class="role-item {{ in_array($role->id, $selectedRoleIds) ? 'selected' : '' }}">
                                                            <div class="form-check">
                                                                <input
                                                                    class="form-check-input role-checkbox"
                                                                    type="checkbox"
                                                                    name="role_ids[]"
                                                                    value="{{ $role->id }}"
                                                                    id="role_{{ $role->id }}"
                                                                    {{ in_array($role->id, $selectedRoleIds) ? 'checked' : '' }} />
                                                                <label
                                                                    class="form-check-label w-100"
                                                                    for="role_{{ $role->id }}">
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="role-icon me-2">
                                                                            <i class="fas fa-user-tag"></i>
                                                                        </div>
                                                                        <div class="flex-grow-1">
                                                                            <strong class="role-name">
                                                                                {{ $role->role_name }}
                                                                            </strong>
                                                                            <small class="text-muted d-block">
                                                                                ID: {{ $role->id }}
                                                                            </small>
                                                                        </div>
                                                                    </div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    @if (! empty($selectedRoles))
                        <div class="col-md-4">
                            <div class="card border-success h-100">
                                <div class="card-header bg-success text-white">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Vai Trò Đã Chọn
                                    <span class="badge bg-white text-success ms-2">{{ count($selectedRoles) }}</span>
                                </div>
                                <div class="card-body">
                                    <div class="selected-roles-list">
                                        @foreach ($selectedRoles as $role)
                                            <div class="selected-role-item mb-2">
                                                <div class="d-flex align-items-center p-2 bg-light rounded">
                                                    <div class="rounded-circle bg-success text-white p-1 me-2">
                                                        <i class="fas fa-user fa-sm"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <strong class="d-block">{{ $role->role_name }}</strong>
                                                        <small class="text-muted">ID: {{ $role->id }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                @if (! empty($selectedRoles))
                    <form method="POST" action="{{ route('rbac.save') }}">
                        @csrf
                        @foreach ($selectedRoleIds as $roleId)
                            <input type="hidden" name="role_ids[]" value="{{ $roleId }}" />
                        @endforeach

                        {{-- Nút Lưu ở trên --}}
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="permission-summary">
                                <span class="badge bg-info fs-6">
                                    <i class="fas fa-shield-alt me-1"></i>
                                    Tổng cộng: {{ count($permissions) }} quyền hạn
                                </span>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i>
                                Lưu Phân Quyền
                            </button>
                        </div>

                        <div class="card">
                            <div class="card-header bg-light">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">
                                        <i class="fas fa-list-check me-2"></i>
                                        Danh sách quyền hạn
                                    </h5>
                                    <div>
                                        <button
                                            type="button"
                                            class="btn btn-outline-primary btn-sm"
                                            id="selectAllPermissions">
                                            <i class="fas fa-check-double me-1"></i>
                                            Chọn tất cả
                                        </button>
                                        <button
                                            type="button"
                                            class="btn btn-outline-secondary btn-sm ms-2"
                                            id="deselectAllPermissions">
                                            <i class="fas fa-times me-1"></i>
                                            Bỏ chọn tất cả
                                        </button>
                                        <button
                                            type="button"
                                            class="btn btn-outline-info btn-sm ms-2"
                                            id="toggleRoleView">
                                            <i class="fas fa-eye me-1"></i>
                                            Xem theo vai trò
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                {{-- Role-specific permission view --}}
                                <div id="roleSpecificView" style="display: none">
                                    <div class="mb-3">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Xem quyền hạn hiện tại của từng vai trò đã chọn
                                        </small>
                                    </div>
                                    @foreach ($selectedRoles as $role)
                                        <div class="role-permissions-section mb-4">
                                            <div class="role-header p-2 bg-light rounded">
                                                <h6 class="mb-0">
                                                    <i class="fas fa-user-tag me-2"></i>
                                                    {{ $role->role_name }}
                                                    <span class="badge bg-primary ms-2">
                                                        {{ count(array_intersect($allRolePermissions[$role->id] ?? [], array_column($permissions->toArray(), 'id'))) }}
                                                        / {{ count($permissions) }}
                                                    </span>
                                                </h6>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                {{-- Main permission table --}}
                                <div id="mainPermissionView">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-bordered">
                                            <thead class="table-primary">
                                                <tr>
                                                    <th style="width: 35%">
                                                        <i class="fas fa-cog me-2"></i>
                                                        Chức năng
                                                    </th>
                                                    <th style="width: 25%">
                                                        <i class="fas fa-key me-2"></i>
                                                        Key
                                                    </th>
                                                    <th style="width: 10%">
                                                        <i class="fas fa-tag me-2"></i>
                                                        Type
                                                    </th>
                                                    <th style="width: 15%" class="text-center">
                                                        <i class="fas fa-toggle-on me-2"></i>
                                                        Trạng thái
                                                    </th>
                                                    <th style="width: 15%" class="text-center">
                                                        <i class="fas fa-users me-2"></i>
                                                        Vai trò có quyền
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($permissions as $permission)
                                                    @php
                                                        $rolesWithPermission = collect($selectedRoles)->filter(
                                                            function ($role) use ($permission, $allRolePermissions) {
                                                                return in_array(
                                                                    $permission->id,
                                                                    $allRolePermissions[$role->id] ?? [],
                                                                );
                                                            },
                                                        );
                                                        $isCommonPermission =
                                                            $rolesWithPermission->count() == count($selectedRoles);
                                                        $hasPartialPermission =
                                                            $rolesWithPermission->count() > 0 &&
                                                            $rolesWithPermission->count() < count($selectedRoles);
                                                    @endphp

                                                    <tr
                                                        class="permission-row {{ $isCommonPermission ? 'table-success' : ($hasPartialPermission ? 'table-warning' : '') }}">
                                                        <td class="align-middle">
                                                            <strong>{{ $permission->name }}</strong>
                                                        </td>
                                                        <td class="align-middle">
                                                            <code class="text-primary">
                                                                {{ $permission->key ?? 'Chưa có key' }}
                                                            </code>
                                                        </td>
                                                        <td class="align-middle">
                                                            <span class="badge bg-secondary">
                                                                {{ $permission->type ?? 'N/A' }}
                                                            </span>
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            <div
                                                                class="form-check form-switch d-flex justify-content-center">
                                                                <input
                                                                    class="form-check-input permission-checkbox"
                                                                    type="checkbox"
                                                                    name="permissions[]"
                                                                    value="{{ $permission->id }}"
                                                                    role="switch"
                                                                    {{ $isCommonPermission ? 'checked' : '' }}
                                                                    {{ $hasPartialPermission ? 'class=indeterminate' : '' }} />
                                                            </div>
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            @if ($rolesWithPermission->count() > 0)
                                                                <span class="badge bg-info">
                                                                    {{ $rolesWithPermission->count() }}/{{ count($selectedRoles) }}
                                                                </span>
                                                                <div class="mt-1">
                                                                    @foreach ($rolesWithPermission as $role)
                                                                        <span class="badge bg-secondary badge-sm me-1">
                                                                            {{ $role->role_name }}
                                                                        </span>
                                                                    @endforeach
                                                                </div>
                                                            @else
                                                                <span class="badge bg-light text-dark">
                                                                    0/{{ count($selectedRoles) }}
                                                                </span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Nút Lưu ở dưới --}}
                        <div class="card-footer bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="permission-actions">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Thay đổi sẽ được áp dụng cho tất cả {{ count($selectedRoles) }} vai trò đã chọn
                                    </small>
                                </div>
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save me-2"></i>
                                    Lưu Phân Quyền Cho
                                </button>
                            </div>
                        </div>
                    </form>
                @else
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <i class="fas fa-user-plus fa-3x text-muted"></i>
                        </div>
                        <h5 class="text-muted">Vui lòng chọn ít nhất một vai trò để bắt đầu quản lý quyền hạn</h5>
                        <p class="text-muted">
                            Bạn có thể chọn nhiều vai trò cùng lúc để quản lý quyền hạn một cách hiệu quả
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

<style>
    .role-item {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 10px;
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
    }

    .role-item:hover {
        border-color: #0d6efd;
        background-color: rgba(13, 110, 253, 0.05);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .role-item.selected {
        border-color: #198754;
        background-color: rgba(25, 135, 84, 0.1);
        box-shadow: 0 0 0 3px rgba(25, 135, 84, 0.2);
    }

    /* Loading indicator */
    .role-item.loading::after {
        content: '';
        position: absolute;
        top: 50%;
        right: 10px;
        width: 16px;
        height: 16px;
        border: 2px solid #f3f3f3;
        border-top: 2px solid #0d6efd;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        transform: translateY(-50%);
    }

    @keyframes spin {
        0% {
            transform: translateY(-50%) rotate(0deg);
        }

        100% {
            transform: translateY(-50%) rotate(360deg);
        }
    }

    .role-item .form-check-input {
        width: 1.5em;
        height: 1.5em;
    }

    .role-icon {
        width: 30px;
        height: 30px;
        background: linear-gradient(45deg, #0d6efd, #6610f2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 12px;
    }

    .selected-role-item .rounded-circle {
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .form-check-input.permission-checkbox {
        width: 2.5em;
        height: 1.25em;
        cursor: pointer;
    }

    .table > :not(caption) > * > * {
        padding: 1rem 0.75rem;
    }

    .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    .table-hover tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.05);
    }

    .form-check-input:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }

    .permission-row.table-success {
        background-color: rgba(25, 135, 84, 0.1) !important;
    }

    .permission-row.table-warning {
        background-color: rgba(255, 193, 7, 0.1) !important;
    }

    .badge-sm {
        font-size: 0.6em;
    }

    .role-permissions-section {
        border-left: 4px solid #0d6efd;
        margin-left: 1rem;
    }

    .indeterminate {
        opacity: 0.5;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Role selection handlers
        const selectAllRolesBtn = document.getElementById('selectAllRoles')
        const deselectAllRolesBtn = document.getElementById('deselectAllRoles')
        const roleCheckboxes = document.querySelectorAll('.role-checkbox')

        // Permission selection handlers
        const selectAllPermissionsBtn = document.getElementById('selectAllPermissions')
        const deselectAllPermissionsBtn = document.getElementById('deselectAllPermissions')
        const permissionCheckboxes = document.querySelectorAll('.permission-checkbox')

        // View toggle
        const toggleRoleViewBtn = document.getElementById('toggleRoleView')
        const roleSpecificView = document.getElementById('roleSpecificView')
        const mainPermissionView = document.getElementById('mainPermissionView')

        // Role selection events
        if (selectAllRolesBtn) {
            selectAllRolesBtn.addEventListener('click', function () {
                roleCheckboxes.forEach((checkbox) => {
                    checkbox.checked = true
                    updateRoleItemSelection(checkbox)
                })
                // Tự động submit form sau khi chọn tất cả
                setTimeout(() => {
                    document.getElementById('roleForm').submit()
                }, 300)
            })
        }

        if (deselectAllRolesBtn) {
            deselectAllRolesBtn.addEventListener('click', function () {
                roleCheckboxes.forEach((checkbox) => {
                    checkbox.checked = false
                    updateRoleItemSelection(checkbox)
                })
                // Tự động submit form sau khi bỏ chọn tất cả
                setTimeout(() => {
                    document.getElementById('roleForm').submit()
                }, 300)
            })
        }

        // Permission selection events
        if (selectAllPermissionsBtn) {
            selectAllPermissionsBtn.addEventListener('click', function () {
                permissionCheckboxes.forEach((checkbox) => {
                    checkbox.checked = true
                })
            })
        }

        if (deselectAllPermissionsBtn) {
            deselectAllPermissionsBtn.addEventListener('click', function () {
                permissionCheckboxes.forEach((checkbox) => {
                    checkbox.checked = false
                })
            })
        }

        // Role item click handler
        roleCheckboxes.forEach((checkbox) => {
            checkbox.addEventListener('change', function () {
                updateRoleItemSelection(this)

                // Tự động submit form khi có thay đổi
                clearTimeout(window.roleFormTimeout)
                window.roleFormTimeout = setTimeout(() => {
                    document.getElementById('roleForm').submit()
                }, 500) // Delay 500ms để tránh submit quá nhiều lần
            })

            // Initialize selection state
            updateRoleItemSelection(checkbox)
        })

        // View toggle handler
        if (toggleRoleViewBtn) {
            let isRoleViewVisible = false
            toggleRoleViewBtn.addEventListener('click', function () {
                isRoleViewVisible = !isRoleViewVisible
                if (isRoleViewVisible) {
                    roleSpecificView.style.display = 'block'
                    mainPermissionView.style.display = 'none'
                    this.innerHTML = '<i class="fas fa-list me-1"></i>Xem tổng quan'
                } else {
                    roleSpecificView.style.display = 'none'
                    mainPermissionView.style.display = 'block'
                    this.innerHTML = '<i class="fas fa-eye me-1"></i>Xem theo vai trò'
                }
            })
        }

        function updateRoleItemSelection(checkbox) {
            const roleItem = checkbox.closest('.role-item')
            if (checkbox.checked) {
                roleItem.classList.add('selected')
            } else {
                roleItem.classList.remove('selected')
            }
        }

        // Show loading state when form is submitting
        function showLoadingState() {
            roleCheckboxes.forEach((checkbox) => {
                const roleItem = checkbox.closest('.role-item')
                roleItem.classList.add('loading')
            })

            // Disable buttons during loading
            if (selectAllRolesBtn) selectAllRolesBtn.disabled = true
            if (deselectAllRolesBtn) deselectAllRolesBtn.disabled = true
        }

        // Add loading state to form submission
        document.getElementById('roleForm').addEventListener('submit', function () {
            showLoadingState()
        })

        // Handle indeterminate state for permissions
        document.querySelectorAll('.permission-checkbox.indeterminate').forEach((checkbox) => {
            checkbox.indeterminate = true
        })
    })
</script>
