<?php
/**
 * @Author yaangvu
 * @Date   Apr 03, 2023
 */

namespace YaangVu\PhpConsulVault\Vault\Auth;

use GuzzleHttp\Exception\GuzzleException;
use YaangVu\PhpConsulVault\Http;

interface AuthStrategy
{

    /**
     * Authenticate Vault system
     * @Author yaangvu
     * @Date   Apr 03, 2023
     *
     * @param Http|null $http
     *
     * @return string
     * @throws GuzzleException
     */
    public function authenticate(?Http $http = null): string;
}