<?php
/**
 * @Author yaangvu
 * @Date   Apr 04, 2023
 */

namespace Yaangvu\PhpConsulVault\Vault\Service;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Yaangvu\PhpConsulVault\Http;
use Yaangvu\PhpConsulVault\Vault\Dto\KVKey\KVKeyDto;
use Yaangvu\PhpConsulVault\Vault\Dto\KVValueDto;
use Yaangvu\PhpConsulVault\Vault\Enum\KVVersionEnum;
use Yaangvu\PhpConsulVault\Vault\Vault;

/**
 * API wrapper for Vault KV version 2
 */
readonly class KV
{
    private Http $http;

    /**
     * @param Http   $http
     * @param string $version version of KV engine
     */
    public function __construct(Vault $vault, private string $version = KVVersionEnum::V2->value)
    {
        $this->http = $vault->http;
    }

    /**
     * Get list keys of secret
     *
     * @param string $path
     *
     * @return array
     * @throws GuzzleException
     * @throws Exception
     */
    public function keys(string $path): array
    {
        $response = $this->http->get($this->buildPath($path), ['list' => true]);

        return KVKeyDto::fromJson($response)->getKeys();
    }

    /**
     * Get value of specific $key
     * @Author yaangvu
     * @Date   Apr 06, 2023
     *
     * @param string $key
     *
     * @return mixed|null
     * @throws GuzzleException
     */
    public function value(string $key): mixed
    {
        $name    = basename($key);
        $path    = trim(str_replace($name, '', $key), '/');
        $secrets = $this->secrets($path);

        return $secrets[$name] ?? null;
    }

    /**
     * Get all secrets
     * @Author yaangvu
     * @Date   Apr 06, 2023
     *
     * @param string $path
     *
     * @return array
     * @throws GuzzleException
     */
    public function secrets(string $path): array
    {
        $response = $this->http->get("$path");

        return KVValueDto::fromJson($response)->getSecrets();
    }

    /**
     * @throws Exception
     */
    protected function buildPath(string $path): string
    {
        return match ($this->version) {
            KVVersionEnum::V1->value => $this->buildPathV1($path),
            KVVersionEnum::V2->value => $this->buildPathV2($path),
            default => throw new Exception('This Vault KV version does not support.')
        };
    }

    /**
     * @param string $path
     *
     * @return string
     */
    protected function buildPathV1(string $path): string
    {
        return $path;
    }

    /**
     * @param $path
     *
     * @return string
     */
    protected function buildPathV2($path): string
    {
        $array  = explode('/', $path);
        $name   = array_shift($array);
        $suffix = implode('/', $array);

        return "$name/metadata/$suffix";
    }
}