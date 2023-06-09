<?php

namespace YaangVu\PhpConsulVault\Laravel\Command;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Console\Attribute\AsCommand;
use YaangVu\PhpConsulVault\Vault\Auth\AuthStrategy;
use YaangVu\PhpConsulVault\Vault\Auth\TokenAuthStrategy;
use YaangVu\PhpConsulVault\Vault\Auth\UserPassAuthStrategy;
use YaangVu\PhpConsulVault\Vault\Enum\AuthMethodEnum;
use YaangVu\PhpConsulVault\Vault\Enum\KVVersionEnum;
use YaangVu\PhpConsulVault\Vault\Service\KV1;
use YaangVu\PhpConsulVault\Vault\Service\KV2;
use YaangVu\PhpConsulVault\Vault\Vault;

#[AsCommand(name: 'yaangvu:vault:kv')]
class VaultKVCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'yaangvu:vault:kv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get key value from Vault system';

    private string $startString = '# Environments were got from Vault ---------------------------------------->>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>';
    private string $endString   = '# <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<---------------------------------------- End Environments were got from Vault';

    /**
     * Execute the console command.
     * @throws Exception
     * @throws GuzzleException
     */
    public function handle(): void
    {
        echo "Start get env from Vault ..................................................................\n";
        $env = File::get(app()->basePath('.env'));
        $env = $this->_deleteOldEnv($env);

        $auth  = $this->auth();
        $vault = new Vault(config('vault.host'), config('vault.api_version'));
        $vault->authenticate($auth);

        if (config('vault.kv_version') === KVVersionEnum::V1->value)
            $kv = new KV1($vault);
        elseif (config('vault.kv_version') === KVVersionEnum::V2->value)
            $kv = new KV2($vault);
        else
            throw new Exception('KV version does not supported');

        $vaultEnv = $this->startString . PHP_EOL;
        foreach (config('vault.paths') as $path) {
            $secrets  = [];
            $vaultEnv .= "#$path" . PHP_EOL;
            $secrets  = array_merge($secrets, $kv->secrets($path));
            foreach ($secrets as $key => $secret)
                $vaultEnv .= "$key=$secret" . PHP_EOL;
        }
        $vaultEnv .= $this->endString . PHP_EOL;


        $this->_putEnvironmentToDotEnv(app()->basePath('.env'), $env . $vaultEnv);
        echo "Finished get env from Vault ..................................................................\n";
    }

    /**
     * @throws Exception
     */
    public function auth(): AuthStrategy
    {
        return match (config('vault.auth_method')) {
            AuthMethodEnum::TOKEN->value => new TokenAuthStrategy(config('vault.token')),
            AuthMethodEnum::USERPASS->value => new UserPassAuthStrategy(config('vault.username'),
                                                                        config('vault.password')),
            default => throw new Exception('This Vault auth method does not support')
        };
    }

    private function _putEnvironmentToDotEnv(string $file, string $env, $mode = FILE_APPEND | LOCK_EX): void
    {
        File::put($file, $env, false);
    }

    private function _deleteOldEnv($env): array|string|null
    {
        $pattern = "/$this->startString[\s\S]*$this->endString/";

        return preg_replace($pattern, '', $env);
    }
}
