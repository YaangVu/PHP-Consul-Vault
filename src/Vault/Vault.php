<?php
/**
 * @Author yaangvu
 * @Date   Apr 03, 2023
 */

namespace YaangVu\PhpConsulVault\Vault;

use GuzzleHttp\Exception\GuzzleException;
use YaangVu\PhpConsulVault\Http;
use YaangVu\PhpConsulVault\Vault\Auth\AuthStrategy;

class Vault
{
    public Http   $http;
    public string $token;

    public function __construct(
        string $url = 'localhost:8200',
        string $version = 'v1',
    )
    {
        $this->http = new Http($url, $version);
    }

    /**
     * @throws GuzzleException
     */
    public function authenticate(AuthStrategy $strategy): static
    {
        $this->token = $strategy->authenticate($this->http);
        $this->http->headers(['X-Vault-Token' => $this->token]);

        return $this;
    }

}