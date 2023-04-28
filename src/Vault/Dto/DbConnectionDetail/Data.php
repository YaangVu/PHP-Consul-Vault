<?php

namespace Yaangvu\PhpConsulVault\Vault\Dto\DbConnectionDetail;

use stdClass;

readonly class Data
{
    public function __construct(
        private array             $allowedRoles,
        private ConnectionDetails $connectionDetails,
        private string            $passwordPolicy,
        private string            $pluginName,
        private string            $pluginVersion,
        private array             $rootCredentialsRotateStatements
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