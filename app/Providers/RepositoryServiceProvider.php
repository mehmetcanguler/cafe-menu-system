<?php

namespace App\Providers;

use App\Contracts\FileRepositoryInterface;
use App\Repositories\FileRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->bind(FileRepositoryInterface::class, FileRepository::class);
    }
}
