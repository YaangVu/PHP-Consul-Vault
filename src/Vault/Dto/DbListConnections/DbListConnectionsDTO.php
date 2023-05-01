<?php

namespace Yaangvu\PhpConsulVault\Vault\Dto\DbListConnections;

use stdClass;

class DbListConnectionsDTO
{
    public function __construct(
        private readonly string $requestId,
        private readonly string $leaseId,
        private readonly bool   $renewable,
        private readonly int    $leaseDuration,
        private readonly Data   $data,
        private readonly mixed  $wrapInfo,
        private readonly mixed  $warnings,
        private readonly mixed  $auth
    )
    {
    }

    public function getRequestId(): string
    {
        return $this->requestId;
    }

    public function getLeaseId(): string
    {
        return $this->leaseId;
    }

    public function getRenewable(): bool
    {
        return $this->renewable;
    }

    public function getLeaseDuration(): int
    {
        return $this->leaseDuration;
    }

    public function getData(): Data
    {
        return $this->data;
    }

    public function getWrapInfo(): mixed
    {
        return $this->wrapInfo;
    }

    public function getWarnings(): mixed
    {
        return $this->warnings;
    }

    public function getAuth(): mixed
    {
        return $this->auth;
    }

    public static function fromJson(stdClass $data): self
    {
        return new self(
            $data->request_id,
            $data->lease_id,
            $data->renewable,
            $data->lease_duration,
            Data::fromJson($data->data),
            $data->wrap_info ?? null,
            $data->warnings ?? null,
            $data->auth ?? null
        );
    }

    public function list(): array
    {
        return $this->data->getKeys();
    }
}

