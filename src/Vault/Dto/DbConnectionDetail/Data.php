<?php

namespace Yaangvu\PhpConsulVault\Vault\Dto\DbConnectionDetail;

use stdClass;

class Data
{
    public function __construct(
        private readonly array             $allowedRoles,
        private readonly ConnectionDetails $connectionDetails,
        private readonly string            $passwordPolicy,
        private readonly string            $pluginName,
        private readonly string            $pluginVersion,
        private readonly array             $rootCredentialsRotateStatements
    )
    {
    }

    public function getAllowedRoles(): array
    {
        return $this->allowedRoles;
    }

    public function getConnectionDetails(): ConnectionDetails
    {
        return $this->connectionDetails;
    }

    public function getPasswordPolicy(): string
    {
        return $this->passwordPolicy;
    }

    public function getPluginName(): string
    {
        return $this->pluginName;
    }

    public function getPluginVersion(): string
    {
        return $this->pluginVersion;
    }

    public function getRootCredentialsRotateStatements(): array
    {
        return $this->rootCredentialsRotateStatements;
    }

    public static function fromJson(stdClass $data): self
    {
        return new self(
            $data->allowed_roles,
            ConnectionDetails::fromJson($data->connection_details),
            $data->password_policy,
            $data->plugin_name,
            $data->plugin_version,
            $data->root_credentials_rotate_statements
        );
    }
}