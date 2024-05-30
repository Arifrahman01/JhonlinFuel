<?php

namespace App\Providers;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (Schema::hasTable('permissions')) {
            $permissions = Permission::all();

            foreach ($permissions as $permission) {
                Gate::define($permission->permission_code, function (User $user) use ($permission) {
                    if ($user->isSuperAdmin()) {
                        return true;
                    }
                    $userPermissions = data_get($user, 'roles.*.permissions.*.permission_code');
                    if (in_array($permission->permission_code, $userPermissions)) {
                        return true;
                    }
                    return false;
                });
            }
        }
    }
}
