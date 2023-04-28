<?php

namespace Yaangvu\PhpConsulVault\Vault\Dto\DbConnectionDetail;

use stdClass;

readonly class DbConnectionDetailDTO
{
    public function __construct(
        private string $requestId,
        private string $leaseId,
        private bool   $renewable,
        private int    $leaseDuration,
        private Data   $data,
        private mixed  $wrapInfo,
        private mixed  $warnings,
        private mixed  $auth
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

    public function get(): ConnectionDetails
    {
        return $this->data->getConnectionDetails();
    }
}

