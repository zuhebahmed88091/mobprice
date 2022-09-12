<?php

namespace App\Providers;

use App;
use DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Models\Setting;
use Config;
use URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // set default string length
        Schema::defaultStringLength(191);

        // Set settings detail
        if (Schema::hasTable('settings')) {
            foreach (Setting::all() as $setting) {
                Config::set('settings.' . $setting->constant, $setting->value);
            }
        }

        $this->bootEnvatoSocialite();

        if (App::environment('flexible')) {
            URL::forceScheme('https');
        }

        if (env("LOG_QUERY")) {
            // clean existing query log file
            $logFile = fopen(storage_path('logs' . DIRECTORY_SEPARATOR . date('Y-m') . '_query.log'), 'w');
            fwrite($logFile, '');
            fclose($logFile);

            // To save the executed queries to file:
            DB::listen(
                function ($sql) {
                    //  example:
                    //  $sql->sql > select * from `ncv_users` where `ncv_users`.`id` = ? limit 1
                    //  $sql->bindings > [5]
                    //  $sql->time > 0.38 (milliseconds)

                    // Process the sql and the bindings:
                    foreach ($sql->bindings as $i => $binding) {
                        if ($binding instanceof \DateTime) {
                            $sql->bindings[$i] = $binding->format('\'Y-m-d H:i:s\'');
                        } else {
                            if (is_string($binding)) {
                                $sql->bindings[$i] = "'$binding'";
                            }
                        }
                    }

                    // Insert bindings into query
                    $query = str_replace(array('%', '?'), array('%%', '%s'), $sql->sql);
                    $query = vsprintf($query, $sql->bindings);

                    // Save the query to file
                    $logFile = fopen(
                        storage_path('logs' . DIRECTORY_SEPARATOR . date('Y-m') . '_query.log'),
                        'a+'
                    );

                    fwrite($logFile, print_r([
                        'query_time' => date('Y-m-d H:i:s'),
                        'query' => $query,
                        'taken_time' => $sql->time . ' milliseconds'
                    ], true));
                    fclose($logFile);
                }
            );
        }
    }

    private function bootEnvatoSocialite()
    {
        $socialite = $this->app->make('Laravel\Socialite\Contracts\Factory');
        $socialite->extend(
            'envato',
            function ($app) use ($socialite) {
                $config = $app['config']['services.envato'];
                return $socialite->buildProvider(EnvatoServiceProvider::class, $config);
            }
        );
    }
}
