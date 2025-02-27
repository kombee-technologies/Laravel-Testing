<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use App\Models\User;



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
     
        // $this->registerPolicies();
        Passport::enablePasswordGrant();
        //Passport::hashClientSecrets();

        Passport::tokensExpireIn(now()->addMinutes(120));
        Passport::refreshTokensExpireIn(now()->addDays(7));
        Passport::personalAccessTokensExpireIn(now()->addMonths(6));
   
   
    

        Gate::define('manage-users', function (User $user) {
            return $user->roles()->where('name', 'admin')->exists();
        });

        
        Gate::define('manage-customers', function (User $user) {
            return $user->roles()->where('name', 'admin')->exists() || 
                   $user->roles()->where('name', 'customer_manager')->exists();
        });
    
        Gate::define('manage-suppliers', function (User $user) {
            return $user->roles()->where('name', 'admin')->exists() || 
                   $user->roles()->where('name', 'supplier_manager')->exists();
        });
    }
}
