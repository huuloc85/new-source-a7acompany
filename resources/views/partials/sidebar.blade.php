<style>
    .nav-item .dropdown-menu {
        max-height: 400px;
        overflow-y: auto;
    }

    .nav-item.dropdown:hover .dropdown-menu {
        display: block;
    }

    /* .dropdown-menu {
        max-height: 100vh;
        overflow-y: auto;
        padding: 0.5rem;
    } */

    @media (max-width: 1200px) {
        .sidebar .sidebar-toggle {
            right: -10px;
        }
    }

    .logo-text {
        font-size: 12px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100%;
        text-align: center;
    }
</style>

@php
    use App\Models\Permission;
    use App\Models\SidebarItem;
    use Illuminate\Support\Facades\Auth;

    /**
     * 1) CONFIG theo permission key (CHA)
     *    - [icon, route]; route = null => GROUP; route != null => ITEM đơn
     *    - KHÔNG hardcode label: label sẽ lấy từ Permission.name
     */
    $menuConfig = [
        // ---- ADMIN (items đơn)
        'view_dashboard' => ['fas fa-home fa-lg', 'admin.home'],
        'view_products' => ['fas fa-boxes fa-lg', 'admin.product.home'],
        // 'view_planning'          => ['fas fa-calendar-alt fa-lg',    'admin.product-plan.index'],
        // 'view_storage'           => ['fas fa-box fa-lg',              'admin.storage.index'],
        'view_today_employees' => ['fas fa-calendar-day fa-lg', 'admin.checkemployee.view-employee-todo'],
        'view_po_list' => ['fas fa-check-square fa-lg', 'admin.checkpo.index'],
        'view_history' => ['fas fa-history fa-lg', 'admin.history.home'],
        'view_schedule' => ['fas fa-calendar-alt fa-lg', 'admin.celender.home'],
        'view_schedule_categories' => ['fas fa-briefcase fa-lg', 'admin.category.home'],

        // ---- ADMIN (groups)
        'view_employee_management' => ['fas fa-users-cog fa-lg', null],
        'view_label_management' => ['fas fa-print fa-lg', null],
        'view_attendance' => ['fas fa-calendar-check fa-lg', null],

        // ---- EMPLOYEE (items đơn)
        'view_employee_schedule' => ['fas fa-calendar-alt fa-lg', 'admin.employee-show.celender'],
        'view_salary' => ['fas fa-money-check-alt fa-lg', 'admin.employee-show.salary'],
        'view_activity_history' => ['fas fa-history fa-lg', 'admin.employee-history-check'],
        'request_label' => ['fas fa-envelopes-bulk fa-lg', 'admin.send-stamp'],
        'storage_export_product' => ['fas fa-box fa-lg', 'admin.storage.index'],
        'scan' => ['fas fa-boxes fa-lg', 'admin.barcode.scan'],
        'view_account_info' => ['fas fa-user fa-lg', 'admin.profile'],
        // ---- EMPLOYEE (items groups)
        'employees_view_attendance' => ['fas fa-calendar-check fa-lg', null],
        'employees_select_products' => ['fas fa-cart-plus', null],
        'employees_view_label' => ['fas fa-print fa-lg', null],
        'view_label_management' => ['fas fa-print fa-lg', null],
    ];

    /**
     * 2) Lấy PERMISSIONS theo role
     */
    $user = Auth::user();
    $roleId = $user->role_id ?? null;

    // Lấy tất cả permissions của role trong sidebar
    $rolePerms = Permission::query()
        ->select('permissions.*')
        ->join('role_permission', 'role_permission.permission_id', '=', 'permissions.id')
        ->where('role_permission.role_id', $roleId)
        ->whereIn('permissions.display_area', ['sidebar', 'both'])
        ->get();

    $permIds = $rolePerms->pluck('id'); // ids permission cha
    $permKeys = $rolePerms->pluck('key'); // collection keys (nếu view cần)
    $permissionTitles = $rolePerms->pluck('name', 'key')->toArray(); // key -> title (label hiển thị)

    /**
     * 3) Lấy CHILDREN từ sidebar_items theo các permission cha
     *    => Đồng thời tạo $sidebarKeys để filter children theo key
     */
    $sidebarItems = SidebarItem::query()->whereIn('permission_id', $permIds)->get();
    $itemsByPermission = $sidebarItems->groupBy('permission_id');
    $sidebarKeys = $sidebarItems->pluck('key')->filter()->unique()->values()->toArray();

    /**
     * 4) Helper: isActive cho route
     */
    $isActive = function (?string $routeName): string {
        return $routeName && request()->routeIs($routeName) ? 'active' : '';
    };

    /**
     * 5) Build NAV theo thứ tự $rolePerms
     *    - Label luôn lấy từ permission title (name)
     *    - GROUP nếu có children; nếu không mà có route thì là ITEM đơn
     */
    $navConfig = [];

    foreach ($rolePerms as $perm) {
        // lấy icon + route từ cấu hình, fallback icon mặc định
        [$iconCfg, $routeCfg] = $menuConfig[$perm->key] ?? ['fas fa-folder fa-lg', null];

        $labelParent = $permissionTitles[$perm->key] ?? $perm->key; // label từ Permission.name
        $iconParent = $iconCfg ?: 'fas fa-folder fa-lg';

        $children = $itemsByPermission->get($perm->id, collect());

        if ($children->isNotEmpty()) {
            // GROUP: map children từ DB, rồi filter theo $sidebarKeys (logic #2)
            $childNodes = $children
                ->map(function ($it) {
                    return [
                        'label' => $it->title, // tiêu đề child từ DB
                        'icon' => $it->icon ?: 'fas fa-circle', // icon child từ DB (fallback)
                        'path' => $it->path, // route child
                        'key' => $it->key, // key child
                    ];
                })
                ->filter(function ($node) use ($sidebarKeys) {
                    return ! empty($node['path']) && ! empty($node['key']) && in_array($node['key'], $sidebarKeys, true); // chỉ giữ child có key hợp lệ
                })
                ->values()
                ->all();

            if (! empty($childNodes)) {
                $navConfig[] = [
                    'label' => $labelParent,
                    'icon' => $iconParent,
                    'children' => $childNodes,
                    'perm_key' => $perm->key,
                ];
            } elseif (! empty($routeCfg)) {
                // không còn child hợp lệ: fallback thành item đơn nếu có route
                $navConfig[] = [
                    'label' => $labelParent,
                    'icon' => $iconParent,
                    'path' => $routeCfg,
                    'key' => $perm->key,
                ];
            }
        } else {
            // ITEM đơn nếu có route
            if (! empty($routeCfg)) {
                $navConfig[] = [
                    'label' => $labelParent,
                    'icon' => $iconParent,
                    'path' => $routeCfg,
                    'key' => $perm->key,
                ];
            }
        }
    }
    $superActive = request()->routeIs('rbac.index') || request()->routeIs('permissions.index');
    $isSuperAdmin = $user && ($user->role->role_name ?? null) === 'Super Admin';
@endphp

<aside class="sidebar sidebar-default navs-rounded-all sidebar-base no-print">
    <div class="sidebar-header d-flex align-items-center justify-content-center flex-column">
        <a href="{{ route('admin.home') }}" class="navbar-brand text-center d-flex flex-column align-items-center">
            <img
                src="{{ asset('assets/img/logos/VVP.png') }}"
                alt=""
                width="70%"
                title="VINH VINH PHAT ONE MEMBER CO.LTD" />
            <div class="logo-text">VINH VINH PHAT ONE MEMBER CO. LTD</div>
        </a>
        <div
            class="sidebar-toggle"
            data-toggle="sidebar"
            data-active="true"
            style="margin: 150px -7px 0 0"
            title="Toggle Sidebar">
            <i class="icon" style="width: 30px; height: 30px">
                <svg width="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M4.25 12.2744L19.25 12.2744"
                        stroke="currentColor"
                        stroke-width="1.5"
                        stroke-linecap="round"
                        stroke-linejoin="round"></path>
                    <path
                        d="M10.2998 18.2988L4.2498 12.2748L10.2998 6.24976"
                        stroke="currentColor"
                        stroke-width="1.5"
                        stroke-linecap="round"
                        stroke-linejoin="round"></path>
                </svg>
            </i>
        </div>
    </div>

    <div class="sidebar-body pt-0 data-scrollbar">
        <div class="sidebar-list">
            <ul class="navbar-nav iq-main-menu" id="sidebar-nav">
                @if ($isSuperAdmin)
                    <li class="nav-item dropdown">
                        <a
                            class="nav-link dropdown-toggle {{ $superActive ? 'active' : '' }}"
                            href="#"
                            role="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                            title="Phân quyền">
                            <i class="fas fa-user-shield fa-lg" style="width: 1.5rem"></i>
                            <span class="ms-3 flex-grow-1">{{ \Illuminate\Support\Str::title('Phân quyền') }}</span>
                        </a>
                        <div class="dropdown-menu">
                            <a
                                class="dropdown-item {{ request()->routeIs('rbac.index') ? 'active' : '' }}"
                                href="{{ route('rbac.index') }}"
                                title="Trang phân quyền">
                                <i class="fas fa-users-cog" style="width: 1.5rem"></i>
                                <span class="flex-grow-1">
                                    {{ \Illuminate\Support\Str::title('Trang phân quyền') }}
                                </span>
                            </a>
                            <a
                                class="dropdown-item {{ request()->routeIs('permissions.index') ? 'active' : '' }}"
                                href="{{ route('permissions.index') }}"
                                title="Trang tạo quyền">
                                <i class="fas fa-key" style="width: 1.5rem"></i>
                                <span class="flex-grow-1">
                                    {{ \Illuminate\Support\Str::title('Trang tạo quyền') }}
                                </span>
                            </a>
                        </div>
                    </li>
                @endif

                @foreach ($navConfig as $nav)
                    @php
                        $hasChildren = ! empty($nav['children'] ?? []);
                    @endphp

                    {{-- ITEM ĐƠN --}}

                    @if (! $hasChildren)
                        <li class="nav-item">
                            <a
                                href="{{ route($nav['path']) }}"
                                class="nav-link {{ $isActive($nav['path']) }}"
                                title="{{ \Illuminate\Support\Str::title($nav['label']) }}">
                                <i class="{{ $nav['icon'] ?? 'fas fa-circle' }}" style="width: 1.5rem"></i>
                                <span class="ms-3 flex-grow-1">
                                    {{ \Illuminate\Support\Str::title($nav['label']) }}
                                </span>
                            </a>
                        </li>
                    @else
                        @php
                            $visibleChildren = array_values(
                                array_filter(
                                    $nav['children'],
                                    fn ($child) => ! empty($child['path']) && ! empty($child['key']),
                                ),
                            );
                            if (count($visibleChildren) === 0) {
                                continue;
                            }

                            $parentActive = false;
                            foreach ($visibleChildren as $ch) {
                                if (! empty($ch['path']) && request()->routeIs($ch['path'])) {
                                    $parentActive = true;
                                    break;
                                }
                            }
                            if (! $parentActive && ! empty($nav['path'] ?? null)) {
                                $parentActive = request()->routeIs($nav['path']);
                            }
                        @endphp

                        <li class="nav-item dropdown">
                            <a
                                class="nav-link dropdown-toggle {{ $parentActive ? 'active' : '' }}"
                                href="#"
                                role="button"
                                data-bs-toggle="dropdown"
                                aria-expanded="false"
                                title="{{ \Illuminate\Support\Str::title($nav['label']) }}">
                                <i class="{{ $nav['icon'] ?? 'fas fa-folder fa-lg' }}" style="width: 1.5rem"></i>
                                <span class="ms-3 flex-grow-1">
                                    {{ \Illuminate\Support\Str::title($nav['label']) }}
                                </span>
                            </a>
                            <div class="dropdown-menu">
                                @foreach ($visibleChildren as $child)
                                    <a
                                        class="dropdown-item {{ $isActive($child['path']) }}"
                                        href="{{ route($child['path']) }}"
                                        title="{{ \Illuminate\Support\Str::title($child['label']) }}">
                                        <i
                                            class="{{ $child['icon'] ?? 'fas fa-angle-right' }}"
                                            style="width: 1.5rem"></i>
                                        <span class="flex-grow-1">
                                            {{ \Illuminate\Support\Str::title($child['label']) }}
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                        </li>
                    @endif
                @endforeach

                <li class="my-5 py-5"></li>
            </ul>
        </div>
    </div>

    <div class="sidebar-footer left-1 mb-3" style="position: absolute; bottom: 0; width: 100%">
        <div class="nav-item text-center">
            <a class="nav-link" href="{{ route('logout') }}">
                <i class="fas fa-right-from-bracket fa-lg" style="width: 1.5rem"></i>
                <span class="nav-link-text ms-1">{{ \Illuminate\Support\Str::title('Đăng xuất') }}</span>
            </a>
        </div>
    </div>
</aside>
