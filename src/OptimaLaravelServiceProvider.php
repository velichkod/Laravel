<?php
/**
 * Created by PhpStorm.
 * User: PirateKing
 * Date: 4/14/2016
 * Time: 12:53 PM
 */

namespace Optimait\Laravel;

use Illuminate\Support\ServiceProvider;

class OptimaLaravelServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        require_once 'Helpers/dateHelpers.php';
        require_once 'Helpers/helpers.php';
        require_once 'Helpers/render.php';
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}