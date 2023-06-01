<?php
/**
 * @Author yaangvu
 * @Date   Apr 03, 2023
 */

namespace YaangVu\PhpConsulVault\Vault\Auth;

use YaangVu\PhpConsulVault\Http;

class TokenAuthStrategy implements AuthStrategy
{
    public function __construct(private readonly string $token)
    {

    }

    /**
     * @inheritDoc
     */
    public function authenticate(?Http $http = null): string
    {
        return $this->token;
    }
}