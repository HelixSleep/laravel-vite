<?php

namespace Innocenzi\Vite;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Innocenzi\Vite\Commands\ExportConfigurationCommand;
use Innocenzi\Vite\Commands\GenerateAliasesCommand;
use Spatie\LaravelPackageTools\Package;

class ViteServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/vite.php', 'vite');

        $this->app->singleton(Vite::class, fn () => new Vite());

        Blade::directive('vite', function ($entryName = null) {
            if (! $entryName) {
                return '<?php echo vite_tags() ?>';
            }

            return sprintf('<?php echo vite_entry(e(%s)); ?>', $entryName);
        });

        Blade::directive('client', function () {
            return '<?php echo vite_client(); ?>';
        });

        Blade::directive('react', function () {
            return '<?php echo vite_react_refresh_runtime(); ?>';
        });
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ExportConfigurationCommand::class,
                GenerateAliasesCommand::class,
            ]);
        }
    }
}
