<?php

namespace App\Providers;

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
        // Activity Logging
        \App\Models\User::observe(\App\Observers\AuditObserver::class);
        \App\Models\GrnSession::observe(\App\Observers\AuditObserver::class);
        \App\Models\BankTransaction::observe(\App\Observers\AuditObserver::class);
        \App\Models\ExpenseSplit::observe(\App\Observers\AuditObserver::class);
        \App\Models\Gdn::observe(\App\Observers\AuditObserver::class);
        \App\Models\Category::observe(\App\Observers\AuditObserver::class);
        \App\Models\Brand::observe(\App\Observers\AuditObserver::class);
        \App\Models\Shop::observe(\App\Observers\AuditObserver::class);
        \App\Models\Item::observe(\App\Observers\AuditObserver::class);

        // Log Artisan Commands
        \Illuminate\Support\Facades\Event::listen(\Illuminate\Console\Events\CommandFinished::class, function ($event) {
            if (in_array($event->command, ['migrate', 'db:seed', 'migrate:fresh', 'migrate:rollback'])) {
                \App\Services\ActivityLogger::command($event->command, [
                    'exit_code' => $event->exitCode,
                    'input' => $event->input->getArguments(),
                ]);
            }
        });

        \Illuminate\Support\Facades\Gate::before(function ($user, $ability) {
            if ($user->role && $user->role->slug === 'admin') {
                return true;
            }

            // Check if the user has the specific permission slug
            if (method_exists($user, 'hasPermission') && $user->hasPermission($ability)) {
                return true;
            }

            return null;
        });
    }
}
