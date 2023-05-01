<?php

namespace Yaangvu\PhpConsulVault\Vault\Service;

use Exception;
use Yaangvu\PhpConsulVault\Http;
use Yaangvu\PhpConsulVault\Vault\Dto\KV2\SecretDto;
use Yaangvu\PhpConsulVault\Vault\Dto\SecretKeys\KeysDto;
use Yaangvu\PhpConsulVault\Vault\Vault;

/**
 * Key Value Version 1 Class
 */
class KV2 implements KV
{
    private Http $http;

    public function __construct(Vault $vault)
    {
        $this->http = $vault->http;
    }

    /**
     * @inheritDoc
     */
    public function keys(string $path): array
    {
        $response = $this->http->get($this->buildPath($path, 'metadata'), ['list' => true]);

        return KeysDto::fromJson($response)->getKeys();
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function secrets(string $path, ?string $version = null): array
    {
        $response = $this->http->get($this->buildPath($path, 'data'), ['version' => $version]);

        return SecretDto::fromJson($response)->getSecrets();
    }

    /**
     * @inheritDoc
     */
    public function value(string $key): mixed
    {
        $name    = basename($key);
        $path    = trim(str_replace($name, '', $key), '/');
        $secrets = $this->secrets($path);

        return $secrets[$name] ?? null;
    }

    /**
     * @param string $path
     * @param string $type : {data|list}
     *
     * @return string
     * @throws Exception
     */
    protected function buildPath(string $path, string $type): string
    {
        if ($type !== 'data' && $type !== 'metadata')
            throw new Exception('Type is not valid');

        $array  = explode('/', $path);
        $name   = array_shift($array);
        $suffix = implode('/', $array);

        return "$name/$type/$suffix";
    }
}