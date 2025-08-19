@extends('layouts.'.$layout)

<style>
    /* Custom styles for permission page */
    .hover-lift {
        transition: transform 0.15s ease;
    }

    .hover-lift:hover {
        transform: translateY(-2px);
    }

    .table td,
    .table th {
        padding: 1rem;
    }

    .badge {
        font-weight: 500;
        letter-spacing: 0.3px;
    }

    .btn {
        transition: all 0.2s ease;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .card {
        border: none;
        border-radius: 10px;
        transition: box-shadow 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 5px 30px rgba(0, 0, 0, 0.1);
    }

    code {
        background: #f8f9fa;
        padding: 0.2em 0.4em;
        border-radius: 3px;
        color: #e83e8c;
        font-size: 0.95em;
    }

    .form-select {
        border-radius: 6px;
        padding: 0.375rem 2.25rem 0.375rem 0.75rem;
        border-color: #dee2e6;
        transition:
            border-color 0.15s ease-in-out,
            box-shadow 0.15s ease-in-out;
    }

    .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    /* Table hover effect */
    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.02);
        transition: background-color 0.2s ease;
    }
</style>

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white p-3 position-relative">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0 text-primary fw-bold">Danh sách quyền</h4>
                        <button
                            class="btn btn-primary px-4 rounded-3 shadow-sm hover-lift"
                            data-bs-toggle="modal"
                            data-bs-target="#permissionModal">
                            <i class="fas fa-plus-circle me-2"></i>
                            Thêm quyền
                        </button>
                        <a
                            href="{{ route('rbac.index') }}"
                            class="btn btn-primary px-4 rounded-3 shadow-sm hover-lift">
                            <i class="fas fa-plus-circle me-2"></i>
                            Trang Phân Quyền
                        </a>
                    </div>
                </div>

                <div class="card-body p-4">
                    <!-- Nav Tabs -->
                    <ul class="nav nav-tabs mb-4" id="permissionTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button
                                class="nav-link active"
                                id="home-tab"
                                data-bs-toggle="tab"
                                data-bs-target="#tab-home"
                                type="button"
                                role="tab"
                                aria-controls="tab-home"
                                aria-selected="true">
                                <i class="fas fa-home me-2"></i>
                                Home
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button
                                class="nav-link"
                                id="sidebar-tab"
                                data-bs-toggle="tab"
                                data-bs-target="#tab-sidebar"
                                type="button"
                                role="tab"
                                aria-controls="tab-sidebar"
                                aria-selected="false">
                                <i class="fas fa-table-columns me-2"></i>
                                Sidebar
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="permissionTabsContent">
                        {{-- TAB HOME (bao gồm Both nếu bạn đã đưa vào $homePermissions) --}}
                        <div class="tab-pane fade show active" id="tab-home" role="tabpanel" aria-labelledby="home-tab">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle border">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="text-center py-3" style="width: 60px">STT</th>
                                            <th class="py-3">Key</th>
                                            <th class="py-3">Tên quyền</th>
                                            <th class="text-center py-3">Loại</th>
                                            <th class="text-center py-3">Hiển thị</th>
                                            <th class="text-center py-3" style="width: 200px">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($homePermissions as $index => $perm)
                                            <tr>
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td><code>{{ $perm->key }}</code></td>
                                                <td>{{ $perm->name }}</td>
                                                <td class="text-center">
                                                    @if ($perm->type === 'admin')
                                                        <span class="badge bg-primary">Admin</span>
                                                    @elseif ($perm->type === 'employee')
                                                        <span class="badge bg-success">Employee</span>
                                                    @else
                                                        <span class="badge bg-secondary">Both</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if ($perm->display_area === 'home')
                                                        <span class="badge bg-info text-dark">Home</span>
                                                    @elseif ($perm->display_area === 'sidebar')
                                                        <span class="badge bg-warning text-dark">Sidebar</span>
                                                    @else
                                                        <span class="badge bg-dark">Both</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <button
                                                        class="btn btn-sm btn-warning rounded-3 shadow-sm hover-lift me-1"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#permissionModal"
                                                        data-id="{{ $perm->id }}"
                                                        data-key="{{ $perm->key }}"
                                                        data-name="{{ $perm->name }}"
                                                        data-type="{{ $perm->type }}"
                                                        data-display="{{ $perm->display_area }}">
                                                        <i class="fas fa-edit me-1"></i>
                                                        Sửa
                                                    </button>

                                                    <form
                                                        action="{{ route('permissions.destroy', $perm) }}"
                                                        method="POST"
                                                        class="d-inline"
                                                        onsubmit="return confirm('Bạn có chắc muốn xóa quyền này?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button
                                                            class="btn btn-sm btn-danger rounded-3 shadow-sm hover-lift">
                                                            <i class="fas fa-trash-alt me-1"></i>
                                                            Xóa
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">
                                                    Không có quyền nào dành cho Home.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- TAB SIDEBAR (bao gồm Both nếu bạn đã đưa vào $sidebarPermissions) --}}
                        <div class="tab-pane fade" id="tab-sidebar" role="tabpanel" aria-labelledby="sidebar-tab">
                            <table class="table table-bordered table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 60px">STT</th>
                                        <th>Key</th>
                                        <th>Tên quyền</th>
                                        <th class="text-center">Loại</th>
                                        <th class="text-center">Sidebar Con</th>
                                        <th style="width: 200px" class="text-center">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($sidebarPermissions as $index => $perm)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td><code>{{ $perm->key }}</code></td>
                                            <td>{{ $perm->name }}</td>
                                            <td class="text-center">
                                                @if ($perm->type === 'admin')
                                                    <span class="badge bg-primary">Admin</span>
                                                @elseif ($perm->type === 'employee')
                                                    <span class="badge bg-success">Employee</span>
                                                @else
                                                    <span class="badge bg-secondary">Both</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($perm->sidebarItems->count())
                                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                                        <select
                                                            class="form-select form-select-sm w-auto"
                                                            id="sidebar-select-{{ $perm->id }}">
                                                            @foreach ($perm->sidebarItems as $item)
                                                                <option
                                                                    value="{{ $item->id }}"
                                                                    data-title="{{ $item->title }}"
                                                                    data-path="{{ $item->path }}"
                                                                    data-icon="{{ $item->icon }}"
                                                                    data-key="{{ $item->key }}">
                                                                    {{ $item->title }} ({{ $item->key }})
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <div class="ms-2">
                                                            <button
                                                                type="button"
                                                                class="btn btn-success btn-sm me-1"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#sidebarChildModal"
                                                                onclick="openCreateSidebarChildFor({{ $perm->id }})"
                                                                title="Thêm sidebar con">
                                                                <i class="fas fa-plus me-1"></i>
                                                                Thêm
                                                            </button>

                                                            <button
                                                                type="button"
                                                                class="btn btn-info btn-sm me-1"
                                                                onclick="openEditSidebarItemForm('{{ $perm->id }}')"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#sidebarChildModal"
                                                                title="Sửa menu">
                                                                <i class="fas fa-edit me-1"></i>
                                                                Sửa
                                                            </button>

                                                            <form
                                                                action="{{ route('sidebar-items.destroy', ['sidebarItem' => '__ID__']) }}"
                                                                method="POST"
                                                                class="d-inline delete-sidebar-form-{{ $perm->id }}"
                                                                onsubmit="return deleteSidebarItem(event, '{{ $perm->id }}')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button
                                                                    type="submit"
                                                                    class="btn btn-danger btn-sm"
                                                                    title="Xoá">
                                                                    <i class="fas fa-trash me-1"></i>
                                                                    Xoá
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                                        <button
                                                            type="button"
                                                            class="btn btn-success btn-sm"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#sidebarChildModal"
                                                            onclick="openCreateSidebarChildFor({{ $perm->id }})">
                                                            <i class="fas fa-plus me-1"></i>
                                                            Thêm
                                                        </button>
                                                    </div>
                                                @endif
                                            </td>

                                            <td class="text-center">
                                                <button
                                                    class="btn btn-sm btn-warning"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#permissionModal"
                                                    data-id="{{ $perm->id }}"
                                                    data-key="{{ $perm->key }}"
                                                    data-name="{{ $perm->name }}"
                                                    data-type="{{ $perm->type }}"
                                                    data-display="{{ $perm->display_area }}">
                                                    <i class="fas fa-edit me-1"></i>
                                                    Sửa
                                                </button>

                                                <form
                                                    action="{{ route('permissions.destroy', $perm) }}"
                                                    method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Bạn có chắc muốn xóa quyền này?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger">
                                                        <i class="fas fa-trash me-1"></i>
                                                        Xóa
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">
                                                Không có quyền nào dành cho Sidebar.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /tab-content -->
                </div>

                {{-- Modal: Tạo / Sửa SidebarItem --}}
                <div
                    class="modal fade"
                    id="sidebarChildModal"
                    tabindex="-1"
                    aria-labelledby="sidebarChildModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <form id="sidebarChildForm" method="POST" action="{{ route('sidebar-items.store') }}">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title" id="sidebarChildModalLabel">Thêm Quyền SidebarItem mới</h5>
                                    <button
                                        type="button"
                                        class="btn-close"
                                        data-bs-dismiss="modal"
                                        aria-label="Đóng"></button>
                                </div>

                                <div class="modal-body">
                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <label class="form-label" for="sidebar_permission_select">Permission</label>
                                            <select
                                                id="sidebar_permission_select"
                                                name="permission_id"
                                                class="form-select"
                                                required>
                                                <option value="">-- Chọn Permission --</option>
                                                @foreach ($sidebarPermissions as $p)
                                                    <option value="{{ $p->id }}" data-key="{{ $p->key }}">
                                                        {{ $p->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <input
                                                type="hidden"
                                                id="permission_id_hidden"
                                                name="permission_id"
                                                value="" />
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label" for="sidebar_child_key">Key (Sidebar)</label>
                                            <input
                                                type="text"
                                                name="key"
                                                id="sidebar_child_key"
                                                class="form-control"
                                                placeholder=""
                                                required />
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label" for="sidebar_child_title">Tiêu đề</label>
                                            <input
                                                type="text"
                                                name="title"
                                                id="sidebar_child_title"
                                                class="form-control"
                                                placeholder=""
                                                required />
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label" for="sidebar_child_icon">Icon</label>
                                            <input
                                                type="text"
                                                name="icon"
                                                id="sidebar_child_icon"
                                                class="form-control"
                                                placeholder="" />
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label" for="sidebar_child_path">Path (Route)</label>
                                            <input
                                                type="text"
                                                name="path"
                                                id="sidebar_child_path"
                                                class="form-control"
                                                placeholder=""
                                                required />
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success">Lưu</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Modal: Tạo / Sửa quyền --}}
                <div
                    class="modal fade"
                    id="permissionModal"
                    tabindex="-1"
                    aria-labelledby="permissionModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <form id="permissionForm" method="POST">
                                @csrf
                                <input type="hidden" name="_method" id="formMethod" value="POST" />

                                <div class="modal-header">
                                    <h5 class="modal-title" id="permissionModalLabel">Sửa Quyền</h5>
                                    <button
                                        type="button"
                                        class="btn-close"
                                        data-bs-dismiss="modal"
                                        aria-label="Đóng"></button>
                                </div>

                                <div class="modal-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label" for="modal_key">Key</label>
                                            <input
                                                type="text"
                                                name="key"
                                                id="modal_key"
                                                class="form-control"
                                                required />
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label" for="modal_name">Tên quyền</label>
                                            <input
                                                type="text"
                                                name="name"
                                                id="modal_name"
                                                class="form-control"
                                                required />
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label" for="modal_type">Loại (type)</label>
                                            <select name="type" id="modal_type" class="form-select" required>
                                                <option value="admin">Admin</option>
                                                <option value="employee">Employee</option>
                                                <option value="both">Both</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label" for="modal_display_area">Khu vực hiển thị</label>
                                            <select
                                                name="display_area"
                                                id="modal_display_area"
                                                class="form-select"
                                                required
                                                onchange="toggleSidebarFields()">
                                                <option value="home">Home</option>
                                                <option value="sidebar">Sidebar</option>
                                                <option value="both">Both</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success">Lưu</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /card -->
        </div>
    </div>
@endsection

<script>
    // Utility functions - optimized
    const DOM = {
        qs: (sel, root = document) => root.querySelector(sel),
        qid: (id) => document.getElementById(id),
        val: (el) => el?.value || "",
        setVal: (el, v = "") => {
            if (el) el.value = v;
        },
        resetValues: (els) => els.forEach(el => DOM.setVal(el, '')),
        setRequired: (ids, required = false) => {
            ids.forEach(id => {
                const el = DOM.qid(id);
                if (el) required ? el.setAttribute('required', 'required') : el.removeAttribute(
                    'required');
            });
        }
    };

    // Method helpers
    const FormHelper = {
        ensureMethod(formEl, method = 'POST') {
            let m = DOM.qs('input[name="_method"]', formEl);
            if (method.toUpperCase() === 'POST') {
                m?.remove();
                return;
            }
            if (!m) {
                m = document.createElement('input');
                Object.assign(m, {
                    type: 'hidden',
                    name: '_method'
                });
                formEl.appendChild(m);
            }
            m.value = method.toUpperCase();
        },

        ensurePermissionHidden(lock = true) {
            const form = DOM.qid('sidebarChildForm');
            if (!form) return;

            const sel = DOM.qid('sidebar_permission_select');
            let hid = DOM.qid('sidebar_permission_id_hidden');

            if (lock) {
                if (!hid) {
                    hid = document.createElement('input');
                    Object.assign(hid, {
                        type: 'hidden',
                        name: 'permission_id',
                        id: 'sidebar_permission_id_hidden'
                    });
                    form.appendChild(hid);
                }
                hid.value = sel?.value || '';
            } else {
                hid?.remove();
            }
        }
    };

    // Permission Modal Functions
    function toggleSidebarFields() {
        const displaySel = DOM.qid('modal_display_area');
        const wrap = DOM.qid('sidebar_fields'); // nếu không có, hàm sẽ bỏ qua
        if (!displaySel || !wrap) return;

        const show = ['sidebar', 'both'].includes(displaySel.value);
        wrap.style.display = show ? '' : 'none';
        DOM.setRequired(['sidebar_permission_id', 'sidebar_key', 'sidebar_title', 'sidebar_path'], show);
    }

    function resetSidebarFields() {
        const fields = ['sidebar_permission_id', 'sidebar_key', 'sidebar_title', 'sidebar_icon', 'sidebar_path'];
        fields.forEach(id => DOM.setVal(DOM.qid(id), ''));
    }

    function setupCreatePermissionForm() {
        const form = DOM.qid('permissionForm');
        DOM.qid('permissionModalLabel').innerText = 'Tạo quyền mới';
        form.action = "{{ route('permissions.store') }}";
        FormHelper.ensureMethod(form, 'POST');

        // Reset form with object mapping
        const fields = {
            modal_key: '',
            modal_name: '',
            modal_type: 'admin',
            modal_display_area: 'home'
        };
        Object.entries(fields).forEach(([id, value]) => {
            const el = DOM.qid(id);
            if (el) el.value = value;
        });

        resetSidebarFields();
        toggleSidebarFields();
    }

    function openCreateForm() {
        setupCreatePermissionForm();
    }

    function openEditPermissionFromButton(btn) {
        const form = DOM.qid('permissionForm');
        const data = ['id', 'key', 'name', 'type', 'display'].reduce((acc, attr) => {
            acc[attr] = btn?.getAttribute(`data-${attr}`) ?? (attr === 'type' || attr === 'display' ? 'admin' :
                '');
            return acc;
        }, {});

        if (!data.id) return setupCreatePermissionForm();

        DOM.qid('permissionModalLabel').innerText = 'Sửa Quyền';
        form.action = `/permissions/${data.id}`;
        FormHelper.ensureMethod(form, 'PUT');

        DOM.setVal(DOM.qid('modal_key'), data.key);
        DOM.setVal(DOM.qid('modal_name'), data.name);
        DOM.qid('modal_type').value = data.type;
        DOM.qid('modal_display_area').value = data.display;

        toggleSidebarFields();

        // Auto switch tab theo khu vực hiển thị khi bấm sửa
        if (data.display === 'sidebar' || data.display === 'both') {
            const sidebarTab = new bootstrap.Tab(DOM.qid('sidebar-tab'));
            sidebarTab.show();
        } else {
            const homeTab = new bootstrap.Tab(DOM.qid('home-tab'));
            homeTab.show();
        }
    }

    // SidebarItem Modal Functions
    const getSidebarInputs = () => ({
        key: DOM.qid('sidebar_child_key') || DOM.qid('sidebar_key'),
        title: DOM.qid('sidebar_child_title') || DOM.qid('sidebar_title'),
        icon: DOM.qid('sidebar_child_icon') || DOM.qid('sidebar_icon'),
        path: DOM.qid('sidebar_child_path') || DOM.qid('sidebar_path')
    });

    function lockPermissionSelect(lock = true) {
        const sel = DOM.qid('sidebar_permission_select');
        if (sel) sel.disabled = !!lock;
        FormHelper.ensurePermissionHidden(lock);
    }

    function setPermissionInSidebarModal(permId, {
        clearFields = false,
        lock = true
    } = {}) {
        const form = DOM.qid('sidebarChildForm');
        const permSelect = DOM.qs('select[name="permission_id"]', form);
        if (!permSelect) return;

        const idStr = String(permId);
        const hasOption = [...permSelect.options].some(o => String(o.value) === idStr);
        permSelect.value = hasOption ? idStr : '';

        if (clearFields) {
            const inputs = getSidebarInputs();
            DOM.resetValues(Object.values(inputs));
        }

        lockPermissionSelect(lock);
        if (lock) FormHelper.ensurePermissionHidden(true);
    }

    function openCreateSidebarChildForm() {
        DOM.qid('sidebarChildModalLabel').innerText = 'Thêm Quyền SidebarItem mới';
        const form = DOM.qid('sidebarChildForm');

        form.action = "{{ route('sidebar-items.store') }}";
        FormHelper.ensureMethod(form, 'POST');

        const inputs = getSidebarInputs();
        DOM.resetValues(Object.values(inputs));

        lockPermissionSelect(false);
        const permSelect = DOM.qs('select[name="permission_id"]', form);
        if (permSelect) permSelect.value = '';

        FormHelper.ensurePermissionHidden(false);
    }

    function openCreateSidebarChildFor(permId) {
        openCreateSidebarChildForm();
        setPermissionInSidebarModal(permId, {
            clearFields: true,
            lock: true
        });
        DOM.qid('sidebar_child_title')?.focus();

        // Khi tạo sidebar item cho 1 permission, tự chuyển sang tab Sidebar cho tiện
        const sidebarTab = new bootstrap.Tab(DOM.qid('sidebar-tab'));
        sidebarTab.show();
    }

    function openEditSidebarItemForm(permId) {
        const select = DOM.qid(`sidebar-select-${permId}`);
        if (!select?.value) return;

        const opt = select.options[select.selectedIndex];
        const itemId = select.value;

        DOM.qid('sidebarChildModalLabel').innerText = 'Chỉnh sửa SidebarItem';
        const form = DOM.qid('sidebarChildForm');
        form.action = "{{ route('sidebar-items.update', ['sidebarItem' => '__ID__']) }}".replace('__ID__', itemId);
        FormHelper.ensureMethod(form, 'PATCH');

        setPermissionInSidebarModal(permId, {
            clearFields: false,
            lock: true
        });

        const inputs = getSidebarInputs();
        const dataset = opt?.dataset || {};
        Object.entries(inputs).forEach(([key, input]) => {
            DOM.setVal(input, dataset[key] || '');
        });

        // Bảo đảm tab Sidebar đang mở
        const sidebarTab = new bootstrap.Tab(DOM.qid('sidebar-tab'));
        sidebarTab.show();
    }

    function deleteSidebarItem(event, permId) {
        event.preventDefault();
        if (!confirm('Bạn có chắc muốn xóa sidebar item này?')) return false;

        const select = DOM.qid(`sidebar-select-${permId}`);
        if (!select?.value) return false;

        const form = event.target.closest('form');
        form.action = "{{ route('sidebar-items.destroy', ['sidebarItem' => '__ID__']) }}".replace('__ID__', select
            .value);
        form.submit();
        return false;
    }

    // Event Delegation
    document.addEventListener('click', (e) => {
        const btn = e.target.closest('[data-bs-target="#permissionModal"]');
        if (!btn) return;

        btn.hasAttribute('data-id') ? openEditPermissionFromButton(btn) : setupCreatePermissionForm();
    });

    // Permission select change handler
    DOM.qid('sidebar_permission_select')?.addEventListener('change', function() {
        DOM.setVal(DOM.qid('sidebar_child_key'), '');
        if (this.disabled) FormHelper.ensurePermissionHidden(true);
    });

    // Initialize
    document.addEventListener('DOMContentLoaded', () => {
        @if ($errors->any())
            new bootstrap.Modal(DOM.qid('permissionModal')).show();
        @endif
        toggleSidebarFields();

        // Form submit safety
        DOM.qid('sidebarChildForm')?.addEventListener('submit', () => {
            const sel = DOM.qid('sidebar_permission_select');
            FormHelper.ensurePermissionHidden(!!sel?.disabled);
        });
    });

    // Display area change handler
    DOM.qid('modal_display_area')?.addEventListener('change', toggleSidebarFields);

    (function() {
        const TAB_STORAGE_KEY = `permissionTabs.active:${location.pathname}`;

        // Khi chuyển tab → lưu ID của nút tab (vd: 'home-tab', 'sidebar-tab')
        document.addEventListener('shown.bs.tab', function(e) {
            // e.target là button .nav-link vừa được kích hoạt
            const activeTabId = e.target?.id;
            if (activeTabId) {
                localStorage.setItem(TAB_STORAGE_KEY, activeTabId);
            }
        });

        // Khi tải lại trang → mở lại tab đã lưu (nếu có)
        document.addEventListener('DOMContentLoaded', function() {
            const savedTabId = localStorage.getItem(TAB_STORAGE_KEY);
            if (!savedTabId) return;

            const savedTabEl = document.getElementById(savedTabId);
            if (savedTabEl) {
                // Hiển thị tab đã lưu
                const tab = new bootstrap.Tab(savedTabEl);
                tab.show();
            }
        });
    })();
</script>
