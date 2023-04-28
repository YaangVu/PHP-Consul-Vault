<?php
/**
 * @Author yaangvu
 * @Date   Apr 03, 2023
 */

namespace Yaangvu\PhpConsulVault\Vault\Auth;

use GuzzleHttp\Exception\GuzzleException;
use Yaangvu\PhpConsulVault\Http;

readonly class UserPassAuthStrategy implements AuthStrategy
{
    public function __construct(private string $username, private string $password, private string $path = 'userpass')
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