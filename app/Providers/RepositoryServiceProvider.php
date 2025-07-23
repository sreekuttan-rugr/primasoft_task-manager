<?php

namespace App\Providers;

use App\Repositories\Interfaces\TaskRepositoryInterface;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Repositories\TaskRepository;
use App\Repositories\CategoryRepository;
use App\Strategies\Scoring\ScoringContext;
use App\Strategies\Scoring\DefaultScoringStrategy;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind repository interfaces to implementations
        $this->app->bind(TaskRepositoryInterface::class, TaskRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        
        // Bind scoring context with default strategy
        $this->app->singleton(ScoringContext::class, function ($app) {
            return new ScoringContext(new DefaultScoringStrategy());
        });
    }

    public function boot(): void
    {
        //
    }
}
