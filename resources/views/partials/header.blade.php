<style>
    .custom-navbar-toggler {
        display: none;
        background: none;
        border: none;
        font-size: 1.5rem;
    }

    @media (max-width: 999px) {
        .custom-navbar-toggler {
            display: block;
        }
    }

    @media (max-width: 567px) {
        .row p {
            margin: 0;
            font-size: 0.9rem;
        }

        .row h2 {
            font-size: 1.2rem;
            margin: 0;
        }
    }

    /* Base navbar styles */
    .navbar-nav .nav-item {
        position: relative;
    }

    /* Nút chuông thông báo */
    .bell-container {
        background-color: #f8d146;
        /* Màu vàng nhạt cho nút chuông */
        color: white;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        transition:
            background-color 0.3s,
            transform 0.3s ease;
    }

    /* Hiệu ứng hover cho nút chuông */
    .bell-container:hover {
        background-color: #f1c40f;
        /* Màu vàng đậm hơn khi hover */
        transform: scale(1.1);
    }

    /* Thông báo chưa đọc */
    #notificationCount {
        font-size: 12px;
        font-weight: bold;
        background-color: #e74c3c;
        /* Đỏ cho badge */
    }

    /* Dropdown thông báo */
    .notification-dropdown {
        padding: 0;
        margin-top: 0.5rem !important;
        width: 320px;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        border: none;
        background-color: #fff;
    }

    /* Header của dropdown */
    .notification-header {
        padding: 15px 20px;
        background-color: #f8f9fa;
        border-bottom: 1px solid #ddd;
        border-radius: 8px 8px 0 0;
    }

    .notification-header h6 {
        color: #333;
        font-weight: 600;
        margin: 0;
    }

    /* Thân thông báo với thanh cuộn */
    .notification-body {
        max-height: 350px;
        overflow-y: auto;
    }

    /* Danh sách thông báo */
    #notificationList {
        margin: 0;
        padding: 0;
    }

    #notificationList .list-group-item {
        padding: 12px 20px;
        border-left: none;
        border-right: none;
        border-color: #eee;
        transition: background-color 0.2s ease;
    }

    /* Hiệu ứng hover cho mỗi mục thông báo */
    #notificationList .list-group-item:hover {
        background-color: #f1f1f1;
    }

    /* Tô đậm tên nhân viên trong thông báo */
    #notificationList .list-group-item strong {
        color: #2c3e50;
        margin-right: 5px;
    }

    /* Thanh cuộn tùy chỉnh cho danh sách thông báo */
    .notification-body::-webkit-scrollbar {
        width: 6px;
    }

    .notification-body::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .notification-body::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 3px;
    }

    .notification-body::-webkit-scrollbar-thumb:hover {
        background: #999;
    }

    /* Màu cho thông báo rỗng */
    .text-muted {
        color: #6c757d !important;
        font-size: 0.9rem;
    }

    /* Animation cho thông báo mới */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    #notificationList .list-group-item {
        animation: fadeIn 0.3s ease-out;
    }
</style>
<nav class="nav navbar navbar-expand-lg navbar-light iq-navbar no-print">
    <div class="container-fluid navbar-inner">
        <a href="{{ route('admin.home') }}" class="navbar-brand">
            <img
                src="{{ asset('assets/img/logos/VVP.png') }}"
                alt=""
                width="100"
            />
        </a>
        <div class="sidebar-toggle" data-toggle="sidebar" data-active="true">
            <i class="icon">
                <svg width="20px" height="20px" viewBox="0 0 24 24">
                    <path
                        fill="currentColor"
                        d="M4,11V13H16L10.5,18.5L11.92,19.92L19.84,12L11.92,4.08L10.5,5.5L16,11H4Z"
                    />
                </svg>
            </i>
        </div>
        <button class="custom-navbar-toggler" id="navbarToggler">
            <i class="fa fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto navbar-list mb-2 mb-lg-0">
                @if (in_array(auth()->user()->role_id, [8, 15]))
                    <li class="nav-item">
                        <!-- Notification Bell Dropdown -->
                        <div class="dropdown d-inline-block">
                            <div
                                class="bell-container rounded-circle d-flex justify-content-center align-items-center"
                                data-bs-toggle="dropdown"
                                aria-expanded="false"
                            >
                                <i class="fa fa-bell text-white"></i>
                                <span
                                    id="notificationCount"
                                    class="badge position-absolute top-0 end-0 translate-middle p-1 rounded-circle"
                                >
                                    0
                                </span>
                            </div>
                            <div
                                class="dropdown-menu dropdown-menu-end notification-dropdown"
                            >
                                <div class="notification-header">
                                    <h6 class="m-0">Thông Báo</h6>
                                </div>
                                <div class="notification-body">
                                    <ul
                                        id="notificationList"
                                        class="list-group list-group-flush"
                                    >
                                        <li class="text-muted text-center p-3">
                                            Không có thông báo
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </li>
                @endif

                <li class="nav-item dropdown ms-2">
                    <!-- User Profile Dropdown -->
                    <a
                        class="nav-link py-0 d-flex align-items-center"
                        href="#"
                        id="navbarDropdown"
                        role="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                    >
                        <div class="d-flex align-items-center">
                            <div
                                class="rounded-circle bg-primary d-flex justify-content-center align-items-center"
                                style="width: 40px; height: 40px"
                            >
                                <i class="fa fa-user text-white"></i>
                            </div>
                            <h6 class="mb-0 caption-title ms-2">
                                {{ Auth()->user()->name }}
                            </h6>
                        </div>
                    </a>
                    <ul
                        class="dropdown-menu dropdown-menu-end"
                        aria-labelledby="navbarDropdown"
                    >
                        <li>
                            <a
                                class="dropdown-item"
                                href="{{ route('admin.profile') }}"
                            >
                                <i class="fas fa-user"></i>
                                Thông Tin Tài Khoản
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider" />
                        </li>
                        <li>
                            <a
                                class="dropdown-item"
                                href="{{ route('logout') }}"
                            >
                                <i class="fas fa-sign-out-alt"></i>
                                Đăng Xuất
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="iq-navbar-header" style="height: 215px">
    <div class="container-fluid iq-container">
        <div class="row">
            <div class="col-md-12">
                <div
                    class="d-flex justify-content-between align-items-center flex-wrap"
                >
                    <div>
                        <h2 style="color: white">
                            Xin Chào {{ Auth()->user()->name }}!
                        </h2>
                        <p style="color: white">
                            Chúc bạn một ngày làm việc hiệu quả
                            <i class="fas fa-smile"></i>
                        </p>
                    </div>
                    <div>
                        @php
                            $icons = [
                                'Giám đốc' => 'fa-user-tie',
                                'Quản lí sản xuất' => 'fa-cogs',
                                'Kế toán' => 'fa-calculator',
                                'Kho' => 'fa-warehouse',
                                'Khuôn' => 'fa-toolbox',
                                'Bảo trì điện' => 'fa-bolt',
                                'Kỹ thuật' => 'fa-wrench',
                                'QA-QC' => 'fa-clipboard-check',
                                'Ngoại Quan' => 'fa-globe',
                                'Sản xuất' => 'fa-industry',
                                'Quản lý' => 'fa-users',
                                'Tổ trưởng sản xuất' => 'fa-chalkboard-teacher',
                                'admin' => 'fa-user-shield',
                                'IT' => 'fa-laptop-code',
                                'Tổ trưởng ngoại quan' => 'fa-user-check',
                            ];
                            $userRole = Auth::user()->role->role_name;
                            $iconClass = isset($icons[$userRole]) ? $icons[$userRole] : 'fa-user';
                        @endphp

                        <a
                            href=""
                            class="btn btn-link btn-soft-light"
                            style="
                                color: white;
                                text-transform: uppercase;
                                text-decoration: none;
                            "
                        >
                            <i class="fas {{ $iconClass }}"></i>
                            {{ $userRole }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="iq-header-img">
        <img
            src="{{ asset('assets/img/dashboard/top-header.png') }}"
            alt="header"
            class="theme-color-default-img img-fluid w-100 h-100 animated-scaleX"
        />
    </div>
</div>
<script src="{{ asset('assets/js/libs.min.js') }}"></script>
<script src="{{ asset('assets/js/hope-ui.js') }}"></script>
<script src="{{ asset('assets/js/modelview.js') }}"></script>
<script src="{{ asset('vendor/Leaflet/leaflet.js') }} "></script>
<script>
    // JavaScript to handle navbar and dropdown toggling
    document.addEventListener('DOMContentLoaded', function () {
        var navbarToggler = document.getElementById('navbarToggler');
        var navbarNav = document.getElementById('navbarNav');
        var dropdownMenu = navbarDropdown.nextElementSibling;

        navbarToggler.addEventListener('click', function () {
            navbarNav.classList.toggle('show');
        });

        // document.addEventListener('click', function(e) {
        //     if (!navbarDropdown.contains(e.target)) {
        //         dropdownMenu.classList.remove('show');
        //     }
        // });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const userId = {{ auth()->id() }};
        const notificationSound = new Audio(
            '{{ asset('assets/music/notification.mp3') }}',
        );

        notificationSound.onerror = function () {
            console.error('Không thể tải tệp âm thanh!');
        };

        const notificationCount = document.getElementById('notificationCount');
        const notificationList = document.getElementById('notificationList');
        let notifications =
            JSON.parse(localStorage.getItem('notifications')) || [];

        // Thêm nút "Xóa tất cả" trên danh sách thông báo
        const clearAllBtn = document.createElement('button');
        clearAllBtn.className = 'btn btn-danger btn-sm w-100 mb-2';
        clearAllBtn.innerHTML =
            '<i class="fas fa-trash"></i> Xóa tất cả thông báo';
        clearAllBtn.onclick = clearAllNotifications;
        notificationList.parentElement.insertBefore(
            clearAllBtn,
            notificationList,
        );

        function clearAllNotifications() {
            if (confirm('Bạn có chắc muốn xóa tất cả thông báo?')) {
                notifications = [];
                localStorage.setItem(
                    'notifications',
                    JSON.stringify(notifications),
                );
                updateNotificationUI();
            }
        }

        function highlightRecord(recordId, index) {
            // Tìm kiếm dòng (row) có thuộc tính data-id tương ứng với recordId
            const targetRow = document.querySelector(
                `tr[data-id="${recordId}"]`,
            );

            if (targetRow) {
                // Đảm bảo dòng này sẽ được highlight
                console.log('Highlighting row:', targetRow);
                targetRow.classList.add('highlight-row'); // Thêm lớp highlight-row vào dòng

                // Lấy tất cả các ô (cells) trong hàng (row)
                const cells = targetRow.children;
                const columnIndexes = [];

                // Lưu chỉ số các cột có data-id hoặc id tương ứng với recordId
                for (let i = 0; i < cells.length; i++) {
                    const cell = cells[i];
                    if (cell.dataset.id === recordId || cell.id === recordId) {
                        columnIndexes.push(i); // Lưu chỉ số cột
                    }
                }

                // Kiểm tra xem columnIndexes đã có chỉ số nào chưa
                console.log('Column indexes to highlight:', columnIndexes);

                // Áp dụng highlight cho tất cả các ô trong các cột có id tương ứng
                const allRows = document.querySelectorAll('tr');
                allRows.forEach((row) => {
                    columnIndexes.forEach((columnIndex) => {
                        const cell = row.children[columnIndex];
                        if (cell) {
                            cell.classList.add('highlight-column');
                        }
                    });
                });

                // Cuộn trang đến bản ghi được highlight
                targetRow.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center',
                });

                // Thêm sự kiện click để xóa highlight khi click vào bất kỳ nơi nào khác
                const removeHighlight = (event) => {
                    // Kiểm tra nếu người dùng click bên ngoài dòng được highlight
                    if (!targetRow.contains(event.target)) {
                        targetRow.classList.remove('highlight-row');

                        // Xóa lớp highlight khỏi các ô trong cột
                        const allCells =
                            document.querySelectorAll('.highlight-column');
                        allCells.forEach((cell) =>
                            cell.classList.remove('highlight-column'),
                        );

                        // Xóa sự kiện click sau khi xóa highlight
                        document.removeEventListener('click', removeHighlight);
                    }
                };

                // Gắn sự kiện click vào document để xử lý khi click ngoài
                document.addEventListener('click', removeHighlight);
            } else {
                console.log('No row found with data-id:', recordId);
            }
        }

        function deleteNotification(index) {
            notifications.splice(index, 1);
            localStorage.setItem(
                'notifications',
                JSON.stringify(notifications),
            );
            updateNotificationUI();
        }

        function updateNotificationUI() {
            notificationList.innerHTML = '';

            if (notifications.length === 0) {
                notificationList.innerHTML = `<li class="text-muted text-center p-3">Không có thông báo</li>`;
            } else {
                notifications.forEach((notification, index) => {
                    const newNotification = document.createElement('li');
                    newNotification.classList.add(
                        'list-group-item',
                        'list-group-item-action',
                        'cursor-pointer',
                    );
                    newNotification.innerHTML = `
                    ${notification.message}
                `;

                    newNotification.addEventListener('click', () => {
                        highlightRecord(notification.recordId, index);
                        // Điều hướng tới route admin.checkstamp với recordId là tham số
                        window.location.href =
                            '{{ route('admin.checkstamp', ['id' => '']) }}/' +
                            notification.recordId;
                    });

                    notificationList.appendChild(newNotification);
                });
            }

            notificationCount.textContent = notifications.length;
        }

        updateNotificationUI();

        window.Echo.channel('user.' + userId).listen('SendStampEvent', (e) => {
            notificationSound
                .play()
                .catch((error) =>
                    console.error('Lỗi khi phát âm thanh:', error),
                );
            notifications.unshift({
                message: e.message,
                recordId: e.recordId,
            });
            localStorage.setItem(
                'notifications',
                JSON.stringify(notifications),
            );
            updateNotificationUI();
        });

        // Kiểm tra nếu có `id` trong URL để làm nổi bật bản ghi
        const urlParams = new URLSearchParams(window.location.search);
        const recordId = urlParams.get('id'); // Lấy `id` từ query string trong URL

        if (recordId) {
            highlightRecord(recordId);
        }
    });
</script>
