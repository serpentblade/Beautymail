<?php

namespace Snowfire\Beautymail;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class BeautymailServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../config/settings.php' => config_path('beautymail.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/../../../public' => public_path('vendor/beautymail'),
        ], 'public');

        $this->loadViewsFrom(__DIR__.'/../../views', 'beautymail');

        $this->app['mailer']->getSwiftMailer()->registerPlugin(new CssInlinerPlugin());

        $this->registerBladeExtensions();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('Snowfire\Beautymail\Beautymail',
            function ($app) {
                return new \Snowfire\Beautymail\Beautymail(config('beautymail.view'));
            });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }

    protected function registerBladeExtensions()
    {
        static $templateStack = [];

        $blade = $this->app['blade.compiler'];

        $blade->directive('beautymail', function($expression) {
            if (Str::startsWith($expression, '(')) {
                $expression = substr($expression, 1, -1);
            }
            return "<?php echo \$__env->make('beautymail::templates.'.${expression},  array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>";
        });

        $blade->directive('startBeautymail', function($expression) use (&$templateStack) {
            if (Str::startsWith($expression, '(')) {
                $expression = substr($expression, 1, -1);
            }

            $matcher = preg_match('#^\'([^\']+)\'(.*)$#', $expression, $matches);

            $templateName = $matches[1];
            $args = $matches[2];

            $templateStack[] = $templateName;
            return "<?php echo \$__env->make('beautymail::templates.${templateName}Start' ${args},  array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>";
        });

        $blade->directive('endBeautymail', function($args) use (&$templateStack) {
            if (Str::startsWith($args, '(')) {
                $args = substr($args, 1, -1);
            }
            $templateName = array_pop($templateStack);
            return "<?php echo \$__env->make('beautymail::templates.${templateName}End' ${args}, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>";
        });
    }
}
