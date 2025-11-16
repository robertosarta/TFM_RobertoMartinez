<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
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
        $this->registerPolicies(); //Por si en un futuro lo uso con policies

        Gate::define('is-admin', function (User $user) {
            return $user->role === 'admin';
        });

        Gate::define('is-business', function (User $user) {
            return $user->role === 'business';
        });
        
        Gate::define('owns-model', function (User $user, $model) {
            if (isset($model->user_id)) {
                return $model->user_id === $user->id;
            }

            if ($model instanceof User) {
                return $model->id === $user->id;
            }

            return false;
        });
    }
}
