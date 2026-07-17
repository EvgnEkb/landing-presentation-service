<?php

namespace App\Providers;

use App\Services\AIAnalysisInterface;
use App\Services\OpenAIAnalysisService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AIAnalysisInterface::class, OpenAIAnalysisService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
