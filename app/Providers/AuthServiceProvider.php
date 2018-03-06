<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

use App\Auth\Model;
use App\Auth\Provider;
use App\Auth\Service;

class AuthServiceProvider extends ServiceProvider {
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot() {
        $this->registerPolicies();

        $this->app->bind('App\Auth\Service', function($app) {
            return new Service();
        });

        $this->app->bind('App\Auth\Model', function ($app) {
            return new Model($app->make('App\Auth\Service'));
        });

        Auth::provider('auth_provider', function($app, array $config) {
            return new Provider($app->make('App\Auth\Model'));
        });

        // Auth::extend('session', function($app, $name, array $config) {
        //     return new \Illuminate\Auth\SessionGuard(
        //         $name,
        //         Auth::createUserProvider($config['provider']),
        //         $app->make('session.store'),
        //         $app->make('request')
        //     );
        // });
    }

    public function register() {

    }
}
