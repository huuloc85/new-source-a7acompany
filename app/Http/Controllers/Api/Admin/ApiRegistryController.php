<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * ApiRegistryController
 *
 * Cung cấp danh sách tất cả API routes đã đăng ký trong routes/api.php.
 * Giúp team FE/BE biết được API nào đã có, method gì, cần quyền gì.
 */
class ApiRegistryController extends Controller
{
    /**
     * Lấy danh sách tất cả API routes.
     *
     * @queryParam search string Tìm theo URI hoặc action
     * @queryParam module string Lọc theo module (employees, stamps, products, ...)
     */
    public function index(Request $request): JsonResponse
    {
        $search = $request->query('search');
        $module = $request->query('module');

        $allRoutes = Route::getRoutes();
        $routes = [];
        $modules = [];

        foreach ($allRoutes as $route) {
            $uri = $route->uri();

            // Chỉ lấy routes thuộc api.php (bắt đầu bằng "api/")
            if (! str_starts_with($uri, 'api/')) {
                continue;
            }

            // Bỏ qua route mặc định sanctum
            if ($uri === 'api/user') {
                continue;
            }

            $methods = array_values(array_filter($route->methods(), fn ($m) => $m !== 'HEAD'));
            $name = $route->getName();
            $action = $route->getActionName();
            $middleware = $route->middleware();

            // Xác định module từ URI
            $routeModule = $this->extractModule($uri);

            // Xác định loại user (Admin/Employee/Shared)
            $userType = $this->determineUserType($uri, $middleware);

            // Xác định quyền cần thiết
            $permission = $this->extractPermission($middleware);

            // Rút gọn action
            $shortAction = str_replace('App\\Http\\Controllers\\', '', $action);

            // Xác định trạng thái BE
            $beStatus = $this->checkBeStatus($action);

            $routeData = [
                'methods' => $methods,
                'uri' => '/'.$uri,
                'name' => $name,
                'action' => $shortAction,
                'module' => $routeModule,
                'user_type' => $userType,
                'permission' => $permission,
                'auth_required' => in_array('auth:sanctum', $middleware),
                'be_status' => $beStatus,
            ];

            // Apply filters
            if ($search && ! str_contains(strtolower($uri), strtolower($search))
                && ! str_contains(strtolower($shortAction), strtolower($search))) {
                continue;
            }

            if ($module && strtolower($routeModule) !== strtolower($module)) {
                continue;
            }

            $routes[] = $routeData;
            if (! in_array($routeModule, $modules)) {
                $modules[] = $routeModule;
            }
        }

        // Sort theo module rồi URI
        usort($routes, function ($a, $b) {
            $moduleCompare = strcmp($a['module'], $b['module']);
            if ($moduleCompare !== 0) {
                return $moduleCompare;
            }

            return strcmp($a['uri'], $b['uri']);
        });

        sort($modules);

        // Tính summary theo module
        $moduleSummary = [];
        foreach ($routes as $route) {
            $mod = $route['module'];
            if (! isset($moduleSummary[$mod])) {
                $moduleSummary[$mod] = 0;
            }
            $moduleSummary[$mod]++;
        }

        return response()->json([
            'success' => true,
            'generated_at' => now()->toIso8601String(),
            'summary' => [
                'total_routes' => count($routes),
                'total_modules' => count($modules),
                'by_module' => $moduleSummary,
            ],
            'modules' => $modules,
            'routes' => $routes,
        ]);
    }

    /**
     * Trích xuất module từ URI.
     * Ví dụ: api/employees/trash → employees
     *         api/employee/todos → employee-todos
     *         api/check-po/export → check-po
     */
    private function extractModule(string $uri): string
    {
        // Loại bỏ prefix "api/"
        $path = preg_replace('#^api/#', '', $uri);

        // Lấy phần đầu tiên
        $segments = explode('/', $path);
        $first = $segments[0] ?? 'other';

        // Nếu là "employee" (nhân viên), gộp thêm segment thứ 2
        if ($first === 'employee' && isset($segments[1])) {
            return 'employee/'.$segments[1];
        }

        // Nếu là admin prefix
        if ($first === 'admin' && isset($segments[1])) {
            return $segments[1];
        }

        return $first;
    }

    /**
     * Xác định loại user: Admin, Employee, hoặc Shared.
     */
    private function determineUserType(string $uri, array $middleware): string
    {
        $middlewareStr = implode(',', $middleware);

        if (str_contains($middlewareStr, 'api.authEmployees')) {
            return 'employee';
        }

        if (str_contains($middlewareStr, 'api.permission')) {
            return 'super_admin';
        }

        if (str_contains($middlewareStr, 'api.can:')) {
            return 'admin';
        }

        // api/login, api/logout → public
        if (str_contains($uri, 'login') || str_contains($uri, 'logout')) {
            return 'public';
        }

        return 'authenticated';
    }

    /**
     * Trích xuất permission key từ middleware.
     */
    private function extractPermission(array $middleware): ?string
    {
        foreach ($middleware as $mw) {
            if (str_starts_with($mw, 'api.can:')) {
                return str_replace('api.can:', '', $mw);
            }
        }

        return null;
    }

    /**
     * Kiểm tra trạng thái BE (controller method đã tồn tại chưa).
     */
    private function checkBeStatus(string $action): string
    {
        if ($action === 'Closure') {
            return 'done';
        }

        // Parse controller@method
        if (str_contains($action, '@')) {
            [$controller, $method] = explode('@', $action);
            if (class_exists($controller) && method_exists($controller, $method)) {
                return 'done';
            }

            return 'missing';
        }

        return 'unknown';
    }
}
