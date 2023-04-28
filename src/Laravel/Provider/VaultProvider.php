<?php

namespace Yaangvu\PhpConsulVault\Laravel\Provider;

use Illuminate\Support\ServiceProvider;
use Yaangvu\PhpConsulVault\Laravel\Command\VaultKVCommand;

class VaultProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('command.kv.vault.yaangvu', function ($app) {
            return new VaultKVCommand();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->publishConfig();
        $configPath = __DIR__ . '/../Config/vault.php';
        $this->mergeConfigFrom($configPath, 'vault');
    }

    /**
     * Publish the config file
     */
    protected function publishConfig(): void
    {
        $configPath = __DIR__ . '/../Config/vault.php';
        $this->publishes([$configPath => $this->app->configPath('vault.php')], 'config');
    }
}
