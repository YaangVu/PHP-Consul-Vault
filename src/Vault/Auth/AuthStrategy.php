<?php
/**
 * @Author yaangvu
 * @Date   Apr 03, 2023
 */

namespace Yaangvu\PhpConsulVault\Vault\Auth;

use GuzzleHttp\Exception\GuzzleException;
use Yaangvu\PhpConsulVault\Http;

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