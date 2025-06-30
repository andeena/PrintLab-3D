<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate; 
use App\Models\Material;   
use App\Models\Order;           
use App\Policies\MaterialPolicy; 
use App\Policies\OrderPolicy;
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
        // Daftarkan policy Anda di sini
        Gate::policy(Material::class, MaterialPolicy::class);
        Gate::policy(Order::class, OrderPolicy::class);
    }
}