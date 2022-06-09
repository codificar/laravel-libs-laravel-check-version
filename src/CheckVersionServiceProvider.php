<?php

namespace Codificar\CheckVersion;

use Illuminate\Support\ServiceProvider;

class CheckVersionServiceProvider extends ServiceProvider 
{
    public function boot()
    {
        // Load routes
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
    }

    public function register()
    {

    }
}