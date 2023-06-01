<?php

namespace YaangVu\PhpConsulVault\Laravel\Provider;

use Illuminate\Support\ServiceProvider;
use YaangVu\PhpConsulVault\Laravel\Command\ConsulKVCommand;

class ConsulProvider extends ServiceProvider
{
    protected string $defaultConfigPath;
    protected string $appConfigPath;

    public function __construct($app)
    {
        parent::__construct($app);
        $this->defaultConfigPath = __DIR__ . '/../Config/consul.php';
        $this->appConfigPath     = $this->app->configPath('consul.php');
    }

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(file_exists($this->appConfigPath) ? $this->appConfigPath : $this->defaultConfigPath,
                               'consul');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->publishes([$this->defaultConfigPath => $this->appConfigPath], 'config');

        $this->commands([ConsulKVCommand::class]);
    }
}
