<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Policies;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Rupadana\ApiService\Models\Token;
use Illuminate\Support\Facades\Gate;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Gate::policy(Role::class, Policies\RolePolicy::class);
        Gate::policy(Permission::class, Policies\PermissionPolicy::class);
        // Gate::policy(Token::class, Policies\TokensPolicy::class);
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

    }
}