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
        //
        $ip = getHostByName(getHostName());
        $file = app()->environmentFilePath();
        $content = file_get_contents($file);
        $content = str_replace('APP_URL=http://', "APP_URL=http://{$ip}", $content);
        file_put_contents($file, $content);
    }
}
