@extends('layouts.'.$layout)

@section('content')
    <div class="container-fluid px-4">
        <div class="card shadow-sm">
            <div class="card-header bg-gradient text-white py-3" style="background-color: #4e73df">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-shield-alt me-2"></i>
                        Quản Lý Phân Quyền Đa Vai Trò
                    </h4>
                </div>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div
                        class="alert alert-success alert-dismissible fade show border-0 rounded-3 shadow-sm"
                        role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>{{ session('success') }}</strong>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (! empty($selectedRoles))
                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-4">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                                            <i class="fas fa-shield-alt text-primary fa-lg"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 text-primary fw-semibold">Tổng số quyền hạn</h6>
                                            <h3 class="mb-0 fw-bold">{{ count($permissions) }}</h3>
                                        </div>
                                    </div>
                                    <div class="vr"></div>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-success bg-opacity-10 p-3 rounded-circle me-3">
                                            <i class="fas fa-users text-success fa-lg"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 text-success fw-semibold">Vai trò đã chọn</h6>
                                            <h3 class="mb-0 fw-bold">{{ count($selectedRoles) }}</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex gap-2">
                                    <!-- Nút mở 2 modal riêng -->
                                    <button
                                        type="button"
                                        class="btn btn-light btn-lg border-2 fw-semibold"
                                        data-bs-toggle="modal"
                                        data-bs-target="#permissionsSidebarModal">
                                        <i class="fas fa-list-check me-2"></i>
                                        Quyền (Sidebar)
                                    </button>
                                    <button
                                        type="button"
                                        class="btn btn-light btn-lg border-2 fw-semibold"
                                        data-bs-toggle="modal"
                                        data-bs-target="#permissionsHomeModal">
                                        <i class="fas fa-list-check me-2"></i>
                                        Quyền (Home)
                                    </button>
                                    <!-- Nút lưu submit form POST bên dưới -->
                                    <button
                                        type="button"
                                        class="btn btn-primary btn-lg fw-semibold px-4"
                                        id="btnSavePermissions">
                                        <i class="fas fa-save me-2"></i>
                                        Lưu Phân Quyền
                                    </button>
                                    <a
                                        href="{{ route('permissions.index') }}"
                                        class="btn btn-dark btn-lg fw-semibold px-4"
                                        style="background: linear-gradient(45deg, #6f42c1, #563d7c); border: none">
                                        <i class="fas fa-user-shield me-2"></i>
                                        Trang Phân Quyền
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Form chọn multiple roles --}}
                <div class="row g-4 mb-4">
                    <div class="col-md-8">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-header bg-white border-bottom">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0 text-primary">
                                        <i class="fas fa-user-tag me-2"></i>
                                        Chọn Vai Trò Quản Lý
                                    </h5>
                                    <span class="badge bg-primary-subtle text-primary">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Có thể chọn nhiều vai trò
                                    </span>
                                </div>
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
                                            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-4 g-3">
                                                @foreach ($roles as $role)
                                                    <div class="col">
                                                        <div
                                                            class="role-item position-relative {{ in_array($role->id, $selectedRoleIds) ? 'selected' : '' }}">
                                                            <div class="form-check p-0">
                                                                <input
                                                                    class="role-checkbox visually-hidden"
                                                                    type="checkbox"
                                                                    name="role_ids[]"
                                                                    value="{{ $role->id }}"
                                                                    id="role_{{ $role->id }}"
                                                                    {{ in_array($role->id, $selectedRoleIds) ? 'checked' : '' }} />
                                                                <label
                                                                    class="role-label w-100 mb-0"
                                                                    for="role_{{ $role->id }}">
                                                                    <div class="role-content">
                                                                        <div class="role-icon-wrapper mb-2">
                                                                            <div class="role-icon">
                                                                                <i class="fas fa-user-tag"></i>
                                                                            </div>
                                                                        </div>
                                                                        <div class="role-info text-center">
                                                                            <h6 class="role-name mb-1">
                                                                                {{ $role->role_name }}
                                                                            </h6>
                                                                            <span class="role-id">
                                                                                ID: {{ $role->id }}
                                                                            </span>
                                                                        </div>
                                                                        <div class="role-status">
                                                                            <i class="fas fa-check-circle"></i>
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
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-header bg-white border-bottom">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0 text-success">
                                            <i class="fas fa-check-circle me-2"></i>
                                            Vai Trò Đã Chọn
                                        </h5>
                                        <span class="badge bg-success-subtle text-success">
                                            {{ count($selectedRoles) }} vai trò
                                        </span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    @foreach ($selectedRoles as $role)
                                        <div class="selected-role-item mb-2">
                                            <div class="d-flex align-items-center p-2 bg-light rounded">
                                                <div class="rounded-circle bg-success text-white p-1 me-2">
                                                    <i class="fas fa-user fa-sm"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <strong>{{ $role->role_name }}</strong>
                                                    <small class="text-muted">ID: {{ $role->id }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                @if (! empty($selectedRoles))
                    {{-- Gom quyền theo display_area --}}
                    @php
                        $permissionsSidebar = collect($permissions)->filter(function ($p) {
                            return in_array($p->display_area, ['sidebar', 'both']);
                        });

                        $permissionsHome = collect($permissions)->filter(function ($p) {
                            return in_array($p->display_area, ['home', 'both']);
                        });
                    @endphp

                    {{-- Form POST lưu quyền --}}
                    <form method="POST" action="{{ route('rbac.save') }}" id="permissionsSaveForm">
                        @csrf
                        @foreach ($selectedRoleIds as $roleId)
                            <input type="hidden" name="role_ids[]" value="{{ $roleId }}" />
                        @endforeach

                        <!-- Modal: Quyền (SIDEBAR) -->
                        <div
                            class="modal fade"
                            id="permissionsSidebarModal"
                            tabindex="-1"
                            aria-labelledby="permissionsSidebarLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header bg-light">
                                        <h5 class="modal-title" id="permissionsSidebarLabel">
                                            <i class="fas fa-list-check me-2"></i>
                                            Danh sách quyền (Sidebar)
                                        </h5>
                                        <button
                                            type="button"
                                            class="btn-close"
                                            data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>

                                    <div class="modal-body p-0">
                                        <div class="p-3 bg-light border-bottom">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <span class="badge bg-primary me-2">
                                                        {{ $permissionsSidebar->count() }} quyền
                                                    </span>
                                                    <small class="text-muted">
                                                        Chọn quyền hạn cho các vai trò đã chọn
                                                    </small>
                                                </div>
                                                <div>
                                                    <button
                                                        type="button"
                                                        class="btn btn-outline-primary btn-sm"
                                                        data-bulk="select"
                                                        data-scope="#permissionsSidebarModal">
                                                        <i class="fas fa-check-double me-1"></i>
                                                        Chọn tất cả
                                                    </button>
                                                    <button
                                                        type="button"
                                                        class="btn btn-outline-secondary btn-sm ms-2"
                                                        data-bulk="deselect"
                                                        data-scope="#permissionsSidebarModal">
                                                        <i class="fas fa-times me-1"></i>
                                                        Bỏ chọn tất cả
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-hover">
                                                    <thead>
                                                        <tr class="bg-light">
                                                            <th class="border-0 rounded-start">
                                                                <span class="text-dark fw-semibold">Chức năng</span>
                                                            </th>
                                                            <th class="border-0">
                                                                <span class="text-dark fw-semibold">Key</span>
                                                            </th>

                                                            {{-- THÊM CỘT SIDEBAR --}}
                                                            <th class="border-0 text-center">
                                                                <span class="text-dark fw-semibold">Sidebar</span>
                                                            </th>

                                                            <th class="border-0 text-center">
                                                                <span class="text-dark fw-semibold">Loại</span>
                                                            </th>
                                                            <th class="border-0 text-center">
                                                                <span class="text-dark fw-semibold">Hiển thị</span>
                                                            </th>
                                                            <th class="border-0 text-center">
                                                                <span class="text-dark fw-semibold">Trạng thái</span>
                                                            </th>
                                                            <th class="border-0 rounded-end text-center">
                                                                <span class="text-dark fw-semibold">
                                                                    Vai trò có quyền
                                                                </span>
                                                            </th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        @foreach ($permissionsSidebar as $permission)
                                                            @php
                                                                $rolesWithPermission = collect($selectedRoles)->filter(
                                                                    fn ($role) => in_array(
                                                                        $permission->id,
                                                                        $allRolePermissions[$role->id] ?? [],
                                                                    ),
                                                                );
                                                                $isCommonPermission =
                                                                    $rolesWithPermission->count() ==
                                                                    count($selectedRoles);
                                                                $hasPartialPermission =
                                                                    $rolesWithPermission->count() > 0 &&
                                                                    ! $isCommonPermission;

                                                                // Lấy danh sách sidebar items theo permission hiện tại (AN TOÀN)
                                                                /** @var \Illuminate\Support\Collection $sidebarItems */
                                                                $sidebarItems = $sidebarItemsByPermission->get(
                                                                    $permission->id,
                                                                    collect(),
                                                                );
                                                            @endphp

                                                            <tr
                                                                class="{{ $isCommonPermission ? 'table-success' : ($hasPartialPermission ? 'table-warning' : '') }}">
                                                                <td>{{ $permission->name }}</td>
                                                                <td>
                                                                    <code class="text-primary">
                                                                        {{ $permission->key }}
                                                                    </code>
                                                                </td>

                                                                {{-- CỘT DROPDOWN SIDEBAR --}}
                                                                <td class="text-center" style="min-width: 220px">
                                                                    @if ($permission->display_area !== 'home' && $sidebarItems->isNotEmpty())
                                                                        <select
                                                                            class="form-select form-select-sm"
                                                                            name="sidebar_item_ids[{{ $permission->id }}]">
                                                                            @foreach ($sidebarItems as $si)
                                                                                <option value="{{ $si->id }}">
                                                                                    {{ $si->key ?? 'Sidebar #'.$si->id }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    @else
                                                                        <select
                                                                            class="form-select form-select-sm"
                                                                            disabled>
                                                                            <option>Không có mục Sidebar</option>
                                                                        </select>
                                                                    @endif
                                                                </td>

                                                                {{-- Các cột còn lại giữ nguyên --}}
                                                                <td class="text-center">
                                                                    @if ($permission->type === 'admin')
                                                                        <span class="badge bg-primary">Admin</span>
                                                                    @elseif ($permission->type === 'employee')
                                                                        <span class="badge bg-success">Employee</span>
                                                                    @else
                                                                        <span class="badge bg-secondary">Both</span>
                                                                    @endif
                                                                </td>
                                                                <td class="text-center">
                                                                    @if ($permission->display_area === 'home')
                                                                        <span class="badge bg-info text-dark">
                                                                            Home
                                                                        </span>
                                                                    @elseif ($permission->display_area === 'sidebar')
                                                                        <span class="badge bg-warning text-dark">
                                                                            Sidebar
                                                                        </span>
                                                                    @else
                                                                        <span class="badge bg-dark">Both</span>
                                                                    @endif
                                                                </td>
                                                                <td class="text-center">
                                                                    <input
                                                                        class="form-check-input permission-checkbox"
                                                                        type="checkbox"
                                                                        name="permissions[]"
                                                                        value="{{ $permission->id }}"
                                                                        {{ $isCommonPermission ? 'checked' : '' }} />
                                                                </td>
                                                                <td class="text-center">
                                                                    @if ($rolesWithPermission->count() > 0)
                                                                        <span class="badge bg-info">
                                                                            {{ $rolesWithPermission->count() }}/{{ count($selectedRoles) }}
                                                                        </span>
                                                                        <div class="mt-1">
                                                                            @foreach ($rolesWithPermission as $role)
                                                                                <span
                                                                                    class="badge bg-secondary badge-sm me-1">
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

                                    <div class="modal-footer bg-light">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                            <i class="fas fa-times me-2"></i>
                                            Đóng
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-2"></i>
                                            Lưu thay đổi
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal: Quyền (HOME) -->
                        <div
                            class="modal fade"
                            id="permissionsHomeModal"
                            tabindex="-1"
                            aria-labelledby="permissionsHomeLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header bg-light">
                                        <h5 class="modal-title" id="permissionsHomeLabel">
                                            <i class="fas fa-list-check me-2"></i>
                                            Danh sách quyền (Home)
                                        </h5>
                                        <button
                                            type="button"
                                            class="btn-close"
                                            data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body p-0">
                                        <div class="p-3 bg-light border-bottom">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <span class="badge bg-primary me-2">
                                                        {{ $permissionsHome->count() }} quyền
                                                    </span>
                                                    <small class="text-muted">
                                                        Chọn quyền hạn cho các vai trò đã chọn
                                                    </small>
                                                </div>
                                                <div>
                                                    <button
                                                        type="button"
                                                        class="btn btn-outline-primary btn-sm"
                                                        data-bulk="select"
                                                        data-scope="#permissionsHomeModal">
                                                        <i class="fas fa-check-double me-1"></i>
                                                        Chọn tất cả
                                                    </button>
                                                    <button
                                                        type="button"
                                                        class="btn btn-outline-secondary btn-sm ms-2"
                                                        data-bulk="deselect"
                                                        data-scope="#permissionsHomeModal">
                                                        <i class="fas fa-times me-1"></i>
                                                        Bỏ chọn tất cả
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-hover">
                                                    <thead>
                                                        <tr class="bg-light">
                                                            <th class="border-0 rounded-start">
                                                                <span class="text-dark fw-semibold">Chức năng</span>
                                                            </th>
                                                            <th class="border-0">
                                                                <span class="text-dark fw-semibold">Key</span>
                                                            </th>
                                                            <th class="border-0 text-center">
                                                                <span class="text-dark fw-semibold">Loại</span>
                                                            </th>
                                                            <th class="border-0 text-center">
                                                                <span class="text-dark fw-semibold">Hiển thị</span>
                                                            </th>
                                                            <th class="border-0 text-center">
                                                                <span class="text-dark fw-semibold">Trạng thái</span>
                                                            </th>
                                                            <th class="border-0 rounded-end text-center">
                                                                <span class="text-dark fw-semibold">
                                                                    Vai trò có quyền
                                                                </span>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($permissionsHome as $permission)
                                                            @php
                                                                $rolesWithPermission = collect($selectedRoles)->filter(
                                                                    fn ($role) => in_array(
                                                                        $permission->id,
                                                                        $allRolePermissions[$role->id] ?? [],
                                                                    ),
                                                                );
                                                                $isCommonPermission =
                                                                    $rolesWithPermission->count() ==
                                                                    count($selectedRoles);
                                                                $hasPartialPermission =
                                                                    $rolesWithPermission->count() > 0 &&
                                                                    ! $isCommonPermission;
                                                            @endphp

                                                            <tr
                                                                class="{{ $isCommonPermission ? 'table-success' : ($hasPartialPermission ? 'table-warning' : '') }}">
                                                                <td>{{ $permission->name }}</td>
                                                                <td>
                                                                    <code class="text-primary">
                                                                        {{ $permission->key }}
                                                                    </code>
                                                                </td>
                                                                <td class="text-center">
                                                                    @if ($permission->type === 'admin')
                                                                        <span class="badge bg-primary">Admin</span>
                                                                    @elseif ($permission->type === 'employee')
                                                                        <span class="badge bg-success">Employee</span>
                                                                    @else
                                                                        <span class="badge bg-secondary">Both</span>
                                                                    @endif
                                                                </td>
                                                                <td class="text-center">
                                                                    @if ($permission->display_area === 'home')
                                                                        <span class="badge bg-info text-dark">
                                                                            Home
                                                                        </span>
                                                                    @elseif ($permission->display_area === 'sidebar')
                                                                        <span class="badge bg-warning text-dark">
                                                                            Sidebar
                                                                        </span>
                                                                    @else
                                                                        <span class="badge bg-dark">Both</span>
                                                                    @endif
                                                                </td>
                                                                <td class="text-center">
                                                                    <input
                                                                        class="form-check-input permission-checkbox"
                                                                        type="checkbox"
                                                                        name="permissions[]"
                                                                        value="{{ $permission->id }}"
                                                                        {{ $isCommonPermission ? 'checked' : '' }} />
                                                                </td>
                                                                <td class="text-center">
                                                                    @if ($rolesWithPermission->count() > 0)
                                                                        <span class="badge bg-info">
                                                                            {{ $rolesWithPermission->count() }}/{{ count($selectedRoles) }}
                                                                        </span>
                                                                        <div class="mt-1">
                                                                            @foreach ($rolesWithPermission as $role)
                                                                                <span
                                                                                    class="badge bg-secondary badge-sm me-1">
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
                                    <div class="modal-footer bg-light">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                            <i class="fas fa-times me-2"></i>
                                            Đóng
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-2"></i>
                                            Lưu thay đổi
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                @else
                    <div class="text-center py-5">
                        <h5 class="text-muted">Vui lòng chọn ít nhất một vai trò</h5>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

<style>
    .role-item {
        border: 1px solid #eaecf4;
        border-radius: 10px;
        padding: 0.75rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        background-color: #fff;
        overflow: hidden;
    }

    .role-item:hover {
        border-color: #4e73df;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(78, 115, 223, 0.08);
    }

    .role-label {
        cursor: pointer;
        display: block;
    }

    .role-content {
        position: relative;
        text-align: center;
    }

    .role-icon-wrapper {
        display: inline-flex;
        justify-content: center;
    }

    .role-icon {
        width: 32px;
        height: 32px;
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 0.875rem;
        transition:
            transform 0.3s ease,
            box-shadow 0.3s ease;
    }

    .role-info {
        margin-top: 0.5rem;
    }

    .role-name {
        color: #2d3748;
        font-weight: 600;
        font-size: 0.95rem;
        margin-bottom: 0.25rem;
        line-height: 1.2;
    }

    .role-id {
        color: #718096;
        font-size: 0.8rem;
        display: block;
    }

    .role-status {
        position: absolute;
        top: -10px;
        right: -10px;
        width: 24px;
        height: 24px;
        background: #1cc88a;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 0.75rem;
        transform: scale(0);
        transition: transform 0.3s ease;
    }

    .role-item.selected {
        border-color: #1cc88a;
        background-color: #f0fff7;
        box-shadow: 0 0 0 1px rgba(28, 200, 138, 0.25);
    }

    .role-item.selected .role-status {
        transform: scale(1);
    }

    .role-item.selected .role-icon {
        background: linear-gradient(135deg, #1cc88a 0%, #169a6b 100%);
        box-shadow: 0 4px 12px rgba(28, 200, 138, 0.2);
    }

    /* Loading indicator */
    .role-item.loading::after {
        content: '';
        position: absolute;
        top: 50%;
        right: 15px;
        width: 18px;
        height: 18px;
        border: 2px solid rgba(78, 115, 223, 0.1);
        border-top: 2px solid #4e73df;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
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
        width: 36px;
        height: 36px;
        background: linear-gradient(135deg, #4e73df, #224abe);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 14px;
        transition: transform 0.2s ease;
    }

    .role-item:hover .role-icon {
        transform: scale(1.05);
    }

    .selected-role-item {
        background: #fff;
        border-radius: 10px;
        transition: transform 0.2s ease;
    }

    .selected-role-item:hover {
        transform: translateY(-1px);
    }

    .form-check-input.permission-checkbox {
        width: 18px;
        height: 18px;
        cursor: pointer;
        border-color: #e9ecef;
        transition: all 0.2s ease;
    }

    .form-check-input.permission-checkbox:checked {
        background-color: #4e73df;
        border-color: #4e73df;
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

    /* Modal styling */
    .modal-xl {
        max-width: 95%;
    }

    .modal-content {
        border: none;
        border-radius: 15px;
    }

    .modal-header {
        border-radius: 15px 15px 0 0;
    }

    .modal-footer {
        border-radius: 0 0 15px 15px;
    }

    /* Custom scrollbar for modal */
    .modal-body::-webkit-scrollbar {
        width: 8px;
    }

    .modal-body::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }

    .modal-body::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 4px;
    }

    .modal-body::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    /* Animation for modal */
    .modal.fade .modal-dialog {
        transition: transform 0.3s ease-out;
    }

    .modal.fade.show .modal-dialog {
        transform: none;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Role selection handlers
        const selectAllRolesBtn = document.getElementById('selectAllRoles')
        const deselectAllRolesBtn = document.getElementById('deselectAllRoles')
        const roleCheckboxes = document.querySelectorAll('.role-checkbox')

        // View toggle (nếu có phần tử)
        const toggleRoleViewBtn = document.getElementById('toggleRoleView')
        const roleSpecificView = document.getElementById('roleSpecificView')
        const mainPermissionView = document.getElementById('mainPermissionView')

        // Nút lưu gửi form POST
        const btnSavePermissions = document.getElementById('btnSavePermissions')
        if (btnSavePermissions) {
            btnSavePermissions.addEventListener('click', function () {
                const form = document.getElementById('permissionsSaveForm')
                if (form) form.submit()
            })
        }

        // Role selection events
        if (selectAllRolesBtn) {
            selectAllRolesBtn.addEventListener('click', function () {
                roleCheckboxes.forEach((checkbox) => {
                    checkbox.checked = true
                    updateRoleItemSelection(checkbox)
                })
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
                setTimeout(() => {
                    document.getElementById('roleForm').submit()
                }, 300)
            })
        }

        // Role item click handler
        roleCheckboxes.forEach((checkbox) => {
            checkbox.addEventListener('change', function () {
                updateRoleItemSelection(this)
                clearTimeout(window.roleFormTimeout)
                window.roleFormTimeout = setTimeout(() => {
                    document.getElementById('roleForm').submit()
                }, 500)
            })
            updateRoleItemSelection(checkbox)
        })

        // View toggle handler
        if (toggleRoleViewBtn && roleSpecificView && mainPermissionView) {
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
                    this.innerHTML = '<i class="fas a-eye me-1"></i>Xem theo vai trò'
                }
            })
        }

        function updateRoleItemSelection(checkbox) {
            const roleItem = checkbox.closest('.role-item')
            if (!roleItem) return
            if (checkbox.checked) {
                roleItem.classList.add('selected')
            } else {
                roleItem.classList.remove('selected')
            }
        }

        function showLoadingState() {
            roleCheckboxes.forEach((checkbox) => {
                const roleItem = checkbox.closest('.role-item')
                if (roleItem) roleItem.classList.add('loading')
            })
            if (selectAllRolesBtn) selectAllRolesBtn.disabled = true
            if (deselectAllRolesBtn) deselectAllRolesBtn.disabled = true
        }

        document.getElementById('roleForm').addEventListener('submit', function () {
            showLoadingState()
        })

        // Bulk check/uncheck trong phạm vi từng modal (Sidebar/Home)
        document.querySelectorAll('[data-bulk]').forEach((btn) => {
            btn.addEventListener('click', function () {
                const scopeSel = this.getAttribute('data-scope')
                const modalEl = document.querySelector(scopeSel)
                if (!modalEl) return
                const checkboxes = modalEl.querySelectorAll('.permission-checkbox')
                const action = this.getAttribute('data-bulk') // "select" | "deselect"
                checkboxes.forEach((cb) => (cb.checked = action === 'select'))
            })
        })

        // Xử lý indeterminate nếu có
        document.querySelectorAll('.permission-checkbox.indeterminate').forEach((checkbox) => {
            checkbox.indeterminate = true
        })

        // Modal handling (giữ nguyên)
        const permissionsSidebarModal = document.getElementById('permissionsSidebarModal')
        const permissionsHomeModal = document.getElementById('permissionsHomeModal')

        ;[permissionsSidebarModal, permissionsHomeModal].forEach((modal) => {
            if (!modal) return
            modal.addEventListener('show.bs.modal', function () {
                /* optional */
            })
            modal.addEventListener('shown.bs.modal', function () {
                /* optional focus */
            })
            const content = modal.querySelector('.modal-content')
            if (content) {
                content.addEventListener('click', function (e) {
                    e.stopPropagation()
                })
            }
        })
    })
</script>
