<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Schema\Blueprint;

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
        Blueprint::macro('tenantAndCreatedBy', function () {
            $this->unsignedBigInteger('tenant_id')->index();
            $this->unsignedBigInteger('created_by')->index();

            $this->foreign('tenant_id')->references('id')->on('tenants');
            $this->foreign('created_by')->references('id')->on('users');
        });
    }
}
