<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespaceapi = 'App\Http\Controllers\Api';
    protected $namespace = 'App\Http\Controllers';
    protected $namespacedoctor = 'App\Http\Controllers\Api\Doctor';
    protected $namespacepatient = 'App\Http\Controllers\Api\Patient';


    /**
     * The path to the "home" route for your application.
     *
     * @var string
     */
    public const HOME = '/dashboard/home';

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
        $this->mapApiRoutes();

        $this->mapWebRoutes();
        $this->mapDoctorRoutes();
        $this->mapPatientRoutes();
        

        
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
            ->group(base_path('routes/web.php'));
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
            ->namespace($this->namespaceapi)
            ->group(base_path('routes/api.php'));
    }
    protected function mapDoctorRoutes()
    {
        Route::prefix('doctor')
            ->middleware('api')
            ->namespace($this->namespacedoctor)
            ->group(base_path('routes/doctor.php'));
    }
    protected function mapPatientRoutes()
    {
        Route::prefix('patient')
            ->middleware('api')
            ->namespace($this->namespacepatient)
            ->group(base_path('routes/patient.php'));
    }
}
