<?php

namespace YaangVu\PhpConsulVault\Laravel\Provider;

use Illuminate\Support\ServiceProvider;
use YaangVu\PhpConsulVault\Laravel\Command\VaultKVCommand;

class VaultProvider extends ServiceProvider
{
    protected string $defaultConfigPath;
    protected string $appConfigPath;

    public function __construct($app)
    {
        parent::__construct($app);
        $this->defaultConfigPath = __DIR__ . '/../Config/vault.php';
        $this->appConfigPath     = $this->app->configPath('vault.php');
    }

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(file_exists($this->appConfigPath) ? $this->appConfigPath : $this->defaultConfigPath,
                               'vault');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->publishes([$this->defaultConfigPath => $this->appConfigPath], 'config');

        $this->commands([VaultKVCommand::class]);
    }
}
