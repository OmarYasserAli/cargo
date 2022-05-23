<?php

namespace App\Providers;

use App\Http\Resources\employerResource;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Schema::defaultStringLength(191);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // employerResource::withoutWrapping();

        // view()->share('categories', Category::get());
        // view()->share('randomCategories', Category::orderBy('id', 'desc')
        // ->take(400) //you can increase or decrease number
        // ->get()
        // ->random(2)); //you can increase number

        // setlocale(LC_ALL, "ar_SA.UTF-8");
        // Carbon::setLocale(config('app.locale')); // sv
    }
}
