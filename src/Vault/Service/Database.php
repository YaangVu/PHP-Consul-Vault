<?php

namespace YaangVu\PhpConsulVault\Vault\Service;

use GuzzleHttp\Exception\GuzzleException;
use YaangVu\PhpConsulVault\Http;
use YaangVu\PhpConsulVault\Vault\Dto\DbConnectionDetail\ConnectionDetails;
use YaangVu\PhpConsulVault\Vault\Dto\DbConnectionDetail\DbConnectionDetailDTO;
use YaangVu\PhpConsulVault\Vault\Dto\DbListConnections\DbListConnectionsDTO;
use YaangVu\PhpConsulVault\Vault\Vault;

class Database
{
    private Http $http;

    public function __construct(private readonly Vault $vault)
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