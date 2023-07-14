<?php

namespace App\Providers;

use App\Repositories\ChangeFontSize\ChangeFontSizeRepository;
use App\Repositories\ChangeFontSize\ChangeFontSizeRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $models = array(
            'User',
            'Question',
            'Answer',
	        'Category',
	        'FeaturedTag',
            'Questionnaire',
            'Tag',
        );
        foreach ($models as $model) {
            $this->app->bind("App\Repositories\\{$model}\\{$model}Repository",
                "App\Repositories\\{$model}\\{$model}RepositoryEloquent");
        }

		// change font size repository
	    $this->app->bind(ChangeFontSizeRepositoryInterface::class, ChangeFontSizeRepository::class);
    }
}
