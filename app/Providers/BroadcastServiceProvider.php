<?php

namespace App\Providers;

use App\Http\Middleware\AttachBearerToken;
use App\Http\Middleware\CheckTokenExpiration;
use G4T\Swagger\Middleware\SetJsonResponseMiddleware;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Broadcast::routes([
            'middleware' => [
                AttachBearerToken::class,
                ThrottleRequests::class.':api',
                SubstituteBindings::class,
                SetJsonResponseMiddleware::class,
                'auth:sanctum',
                CheckTokenExpiration::class,
            ],
        ]);

        require base_path('routes/channels.php');
    }
}
