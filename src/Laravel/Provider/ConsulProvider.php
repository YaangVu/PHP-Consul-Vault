<?php

namespace Yaangvu\PhpConsulVault\Laravel\Provider;

use Exception;
use Illuminate\Support\ServiceProvider;
use Yaangvu\PhpConsulVault\Laravel\Command\ConsulKVCommand;

class ConsulProvider extends ServiceProvider
{
    public string $configPath;

    public function __construct($app)
    {
        parent::__construct($app);
        $this->configPath = $this->app->configPath('consul.php');
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->publishConfig();
        $this->commands(
            [
                ConsulKVCommand::class
            ]
        );
    }

    /**
     * Register the application services.
     *
     * @return void
     * @throws Exception
     */
    public function register(): void
    {
        $this->_config();
    }

    private function publishConfig(): void
    {
        $path = $this->_getConfigPath();
        $this->publishes([$path => $this->app->configPath('consul.php')], 'config');
    }

    private function _getConfigPath(): string
    {
        return __DIR__ . '/../Config/vault.php';
    }

    private function _config(): void
    {
        $path = file_exists($this->configPath) ? $this->configPath : $this->_getConfigPath();

        $this->mergeConfigFrom($path, 'consul');
    }
}
