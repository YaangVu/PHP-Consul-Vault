<?php

namespace Yaangvu\PhpConsulVault\Vault\Service;

use GuzzleHttp\Exception\GuzzleException;
use Yaangvu\PhpConsulVault\Http;
use Yaangvu\PhpConsulVault\Vault\Dto\DbConnectionDetail\ConnectionDetails;
use Yaangvu\PhpConsulVault\Vault\Dto\DbConnectionDetail\DbConnectionDetailDTO;
use Yaangvu\PhpConsulVault\Vault\Dto\DbListConnections\DbListConnectionsDTO;
use Yaangvu\PhpConsulVault\Vault\Vault;

readonly class Database
{
    private Http $http;

    public function __construct(private Vault $vault)
    {
        $this->http = $this->vault->http;
    }


    /**
     * Get list database connections
     *
     * @param string $path
     *
     * @return string[]
     * @throws GuzzleException
     */
    public function list(string $path): array
    {
        $response = $this->http->get("$path/config", ['list' => true]);

        return DbListConnectionsDto::fromJson($response)->list();
    }

    /**
     * @param string $path
     *
     * @return ConnectionDetails
     * @throws GuzzleException
     */
    public function get(string $path): ConnectionDetails
    {
        $array  = explode('/', $path);
        $name   = array_shift($array);
        $suffix = implode('/', $array);
        $path   = "$name/config/$suffix";

        $response = $this->http->get("$path");

        return DbConnectionDetailDTO::fromJson($response)->get();
    }
}