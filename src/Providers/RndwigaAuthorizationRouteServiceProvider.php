<?php

namespace Rndwiga\Authentication\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Rndwiga\Authentication\ModuleHelper;

class RndwigaAuthorizationRouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'Rndwiga\Authentication\Http\Controllers';
    protected $namespaceApi = 'Rndwiga\Authentication\Api\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
       // $this->mapApiRoutes();

       // $this->mapWebRoutes();

        $this->loadApiRoutes();
        $this->loadWebRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(ModuleHelper::getWebRoutes());
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(ModuleHelper::getApiRoutes());
    }

    protected function loadApiRoutes(){
        $publicRoutes = ModuleHelper::getPrivateRoutes();
        if ($publicRoutes){
            foreach ($publicRoutes as $key => $route) {

                if (file_exists($route)){
                    Route::prefix('api')
                        ->middleware(['apiHeader','api'])
                        ->namespace($this->namespaceApi)
                        ->group($route);
                }
            }
        }
    }

    protected function loadWebRoutes(){
        $publicRoutes = ModuleHelper::getPublicRoutes();
        if ($publicRoutes){
            foreach ($publicRoutes as $key => $route) {

                if (file_exists($route)){
                    Route::namespace($this->namespace)
                        //->prefix('api')
                        ->middleware('web')
                        //->namespace($this->namespace)
                        ->group($route);
                }
            }
        }
    }
}
