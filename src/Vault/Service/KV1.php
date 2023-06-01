<?php

namespace YaangVu\PhpConsulVault\Vault\Service;

use YaangVu\PhpConsulVault\Http;
use YaangVu\PhpConsulVault\Vault\Dto\SecretKeys\KeysDto;
use YaangVu\PhpConsulVault\Vault\Dto\KV1\SecretDto;
use YaangVu\PhpConsulVault\Vault\Vault;

/**
 * Key Value Version 1 Class
 */
class KV1 implements KV
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
        $response = $this->http->get($path, ['list' => true]);

        return KeysDto::fromJson($response)->getKeys();
    }

    /**
     * @inheritDoc
     */
    public function secrets(string $path): array
    {
        $response = $this->http->get($path);

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
}