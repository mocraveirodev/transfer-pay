<?php

namespace Src\Infrastructure\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class TransactionRouteProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->routes();
    }

    protected function routes(): void
    {
        Route::prefix('api')
            ->group(base_path('src/Presentation/Routes/api.php'));
    }
}
