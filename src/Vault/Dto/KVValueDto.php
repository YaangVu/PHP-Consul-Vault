<?php
/**
 * @Author yaangvu
 * @Date   Apr 05, 2023
 */

namespace Yaangvu\PhpConsulVault\Vault\Dto;

final readonly class KVValueDto
{
    public function __construct(
        private string $requestId,
        private string $leaseId,
        private bool   $renewable,
        private int    $leaseDuration,
        private object $data,
        private mixed  $wrapInfo,
        private mixed  $warnings,
        private mixed  $auth
    )
    {
    }

    public static function fromJson(\stdClass $data): self
    {
        return new self(
            $data->request_id,
            $data->lease_id,
            $data->renewable,
            $data->lease_duration,
            $data->data,
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

    /**
     * @return array{key: string,value: mixed}
     */
    public function getSecrets(): array
    {
        return (array)$this->getData();
    }

    public function getData(): object
    {
        return $this->data;
    }
}