<?php

namespace Yaangvu\PhpConsulVault\Vault\Dto\DbConnectionDetail;

use stdClass;

readonly class ConnectionDetails
{
    public function __construct(private string $backend, private string $connectionUrl)
    {
    }

    public function getBackend(): string
    {
        return $this->backend;
    }

    public function getConnectionUrl(): string
    {
        return $this->connectionUrl;
    }

    public static function fromJson(stdClass $data): self
    {
        return new self(
            $data->backend,
            $data->connection_url
        );
    }
}