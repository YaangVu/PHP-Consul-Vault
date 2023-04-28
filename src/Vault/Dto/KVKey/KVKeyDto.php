<?php
/**
 * @Author yaangvu
 * @Date   Apr 04, 2023
 */

namespace Yaangvu\PhpConsulVault\Vault\Dto\KVKey;

use stdClass;

final readonly class KVKeyDto
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

    public function getKeys(): array
    {
        return $this->data->getKeys();
    }
}

