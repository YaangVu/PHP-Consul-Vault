<?php

namespace YaangVu\PhpConsulVault\Vault\Service;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use YaangVu\PhpConsulVault\Vault\Vault;

interface KV
{
    public function __construct(Vault $vault);

    /**
     * Get list keys of secret
     *
     * @param string $path
     *
     * @return array
     * @throws GuzzleException
     * @throws Exception
     */
    public function keys(string $path): array;

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
    public function secrets(string $path): array;

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
    public function value(string $key): mixed;
}