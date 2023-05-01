<?php

namespace Yaangvu\PhpConsulVault\Laravel\Command;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Console\Attribute\AsCommand;
use Yaangvu\PhpConsulVault\Vault\Auth\AuthStrategy;
use Yaangvu\PhpConsulVault\Vault\Auth\TokenAuthStrategy;
use Yaangvu\PhpConsulVault\Vault\Auth\UserPassAuthStrategy;
use Yaangvu\PhpConsulVault\Vault\Enum\AuthMethodEnum;
use Yaangvu\PhpConsulVault\Vault\Enum\KVVersionEnum;
use Yaangvu\PhpConsulVault\Vault\Service\KV1;
use Yaangvu\PhpConsulVault\Vault\Service\KV2;
use Yaangvu\PhpConsulVault\Vault\Vault;

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

        $secrets   = [];
        $envString = $this->startString . PHP_EOL;
        foreach (config('vault.paths') as $path) {
            $envString .= "#$path" . PHP_EOL;
            $secrets   = array_merge($secrets, $kv->secrets($path));
            foreach ($secrets as $key => $secret)
                $envString .= "$key=$secret" . PHP_EOL;
        }
        $envString .= $this->endString . PHP_EOL;


        $this->_putEnvironmentToDotEnv($env, $envString);
        echo "Finished get env from Vault ..................................................................\n";
    }

    /**
     * @throws Exception
     */
    public function auth(): AuthStrategy
    {
        return match (config('vault.auth_method')) {
            AuthMethodEnum::TOKEN => new TokenAuthStrategy(config('vault.token')),
            AuthMethodEnum::USERPASS => new UserPassAuthStrategy(config('vault.username'), config('vault.password')),
            default => throw new Exception('This Vault auth method does not support')
        };
    }

    private function _putEnvironmentToDotEnv(string $file, string $env, $mode = FILE_APPEND | LOCK_EX): void
    {
        File::put($file, $env, true);
    }

    private function _deleteOldEnv($env): array|string|null
    {
        $pattern = "/$this->startString[\s\S]*$this->endString/";

        return preg_replace($pattern, '', $env);
    }
}
