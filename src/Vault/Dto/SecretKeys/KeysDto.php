<?php

namespace Yaangvu\PhpConsulVault\Vault\Dto\SecretKeys;

use stdClass;

final class KeysDto
{
    public function __construct(
        private readonly ?string      $requestId,
        private readonly ?string      $leaseId,
        private readonly ?bool        $renewable,
        private readonly ?int         $leaseDuration,
        private readonly ?KeysDataDto $data,
        private readonly mixed        $wrapInfo,
        private readonly mixed        $warnings,
        private readonly mixed        $auth
    )
    {
    }

    public function getRequestId(): ?string
    {
        return $this->requestId;
    }

    public function getLeaseId(): ?string
    {
        return $this->leaseId;
    }

    public function getRenewable(): ?bool
    {
        return $this->renewable;
    }

    public function getLeaseDuration(): ?int
    {
        return $this->leaseDuration;
    }

    public function getData(): ?KeysDataDto
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
            $data->request_id ?? null,
            $data->lease_id ?? null,
            $data->renewable ?? null,
            $data->lease_duration ?? null,
            ($data->data ?? null) !== null ? KeysDataDto::fromJson($data->data) : null,
            $data->wrap_info ?? null,
            $data->warnings ?? null,
            $data->auth ?? null
        );
    }

    /**
     * Get list of keys
     * @return array
     */
    public function getKeys(): array
    {
        return (array)$this->getData()->getKeys();
    }
}

