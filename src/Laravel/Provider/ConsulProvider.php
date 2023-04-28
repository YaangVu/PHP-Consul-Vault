<?php

namespace Yaangvu\PhpConsulVault\Laravel\Provider;

use Illuminate\Support\ServiceProvider;
use Yaangvu\PhpConsulVault\Laravel\Command\ConsulKVCommand;

class ConsulProvider extends ServiceProvider
{
    protected string $defaultConfig;

    public function __construct($app)
    {
        parent::__construct($app);
        $this->defaultConfig = __DIR__ . '/../Config/consul.php';
    }

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom($this->defaultConfig, 'consul');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->publishes([$this->defaultConfig => $this->app->configPath('consul.php')], 'config');
        $this->mergeConfigFrom($this->defaultConfig, 'vault');
        $this->commands([ConsulKVCommand::class]);
    }
}
