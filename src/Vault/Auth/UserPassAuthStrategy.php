<?php
/**
 * @Author yaangvu
 * @Date   Apr 03, 2023
 */

namespace YaangVu\PhpConsulVault\Vault\Auth;

use GuzzleHttp\Exception\GuzzleException;
use YaangVu\PhpConsulVault\Http;

class UserPassAuthStrategy implements AuthStrategy
{
    public function __construct(private readonly string $username, private readonly string $password, private readonly string $path = 'userpass')
    {

    }

    /**
     * @inheritDoc
     *
     * @throws GuzzleException
     */
    public function authenticate(Http $http = null): string
    {
        $response = $http->post("auth/$this->path/login/$this->username",
                                ['password' => $this->password, 'debug' => true]);

        return $response->auth->client_token;
    }
}