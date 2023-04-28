<?php

namespace Yaangvu\PhpConsulVault\Laravel\Command;

use DCarbone\PHPConsulAPI\Config;
use DCarbone\PHPConsulAPI\Consul;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;
use Yaangvu\PhpConsulVault\Consul\ConsulClient;

#[AsCommand(name: 'yaangvu:consul:kv')]
class ConsulKVCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'yaangvu:consul:kv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get environment from Consul system';

    private string $startString = '# Environment got from Consul ---------------------------------------->>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>';
    private string $endString   = '# <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<---------------------------------------- End Environment got from Consul';

    public string       $configPath;
    public string       $envPath;
    public ConsulClient $client;
    public array        $directories = [];
    public string       $uri;
    public string       $token;
    public string       $scheme;
    public string       $dc;
    public array        $paths;
    public bool         $recursive;

    //
    public function __construct()
    {
        parent::__construct();
        $this->configPath = app()->configPath('consul.php');
        $this->envPath    = app()->basePath('.env');
        $this->_config();
    }

    /**
     * Execute the console command.
     *
     * @return void
     * @throws GuzzleException
     */
    public function handle(): void
    {
        $config = Config::newDefaultConfig();
        $config->setAddress('');

        $consul = new Consul($config);

        $consul->KV->Keys()->Value;


        echo "Starting get env from Consul ..................................................................\n";

        $env = File::get(app()->basePath('.env'));

        $env = $this->_deleteOldEnv($env);

        $envConsul = "$this->startString\n";

        $consulKeys = $this->_getKeysFromConsul();

        foreach ($this->paths as $path) {
            $path                = trim($path, '/');
            $this->directories[] = "$path";
            $envConsul           .= "\n# $path/ \n";
            foreach ($consulKeys as $consulKey) {
                $envConsul = $this->_genEnvString($path, $consulKey, $envConsul, $this->recursive);
            }
        }

        $envConsul .= $this->endString;

        $env .= $envConsul;

        $this->putEnvironmentToDotEnv(app()->basePath('.env'), $env);

        echo "Finished get env from Consul ..................................................................\n";
    }

    public function putEnvironmentToDotEnv(string $file, string $env, $mode = FILE_APPEND | LOCK_EX): void
    {
        File::put($file, $env, true);
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    private function _getKeysFromConsul(): ?array
    {
        $this->client = new ConsulClient($this->uri, $this->token, $this->scheme, $this->dc);
        $response     = ConsulClient::$consul->KV->Keys();
        if ($response->Err !== null)
            throw new Exception("Can not get consul keys");

        // Return List of Consul Keys available
        return $response->Value;
    }

    /**
     * @throws GuzzleException
     */
    private function _genEnvString(string $needKey, string $consulKey, string &$envString = '',
                                   bool   $recursive = true): string
    {
        // If not match to $needKey
        if (!Str::startsWith($consulKey, $needKey))
            return $envString;

        // If Key is folder
        if (Str::endsWith($consulKey, '/')) {
            $this->directories[] = trim($consulKey, '/');

            return $envString . "# $consulKey\n";
        }

        // If it can be not recursive, reject if level > 1
        $replacedKey = Str::replaceFirst("$needKey/", '', $consulKey);
        if (!$recursive && Str::contains($replacedKey, '/'))
            return $envString;

        $dir = Str::of($consulKey)->dirname();
        // Add directory to comment if not exist
        if (!in_array($dir, $this->directories)) {
            $envString           .= "# $dir/ \n";
            $this->directories[] = $dir->toString();
        }

        // Get value of Consul Key via API
        $envKey    = Str::of($consulKey)->basename();
        $envValue  = $this->client->get($consulKey);
        $envString .= $envKey . "=" . $envValue . "\n";

        return $envString;
    }

    private function _deleteOldEnv($env): array|string|null
    {
        $pattern = "/$this->startString[\s\S]*$this->endString/";

        return preg_replace($pattern, '', $env);
    }

    private function _config(): void
    {
        $this->uri       = config("consul.uri");
        $this->token     = config("consul.token");
        $this->paths     = config("consul.paths") ?? [];
        $this->scheme    = config("consul.scheme");
        $this->dc        = config("consul.dc");
        $this->recursive = config("consul.recursive") ?? false;
    }
}
