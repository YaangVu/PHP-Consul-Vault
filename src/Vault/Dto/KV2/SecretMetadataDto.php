<?php

namespace Yaangvu\PhpConsulVault\Vault\Dto\KV2;

use stdClass;

final class SecretMetadataDto
{
    public function __construct(
        private readonly ?string $createdTime,
        private readonly mixed   $customMetadata,
        private readonly ?string $deletionTime,
        private readonly ?bool   $destroyed,
        private readonly ?int    $version
    )
    {
    }

    public function getCreatedTime(): ?string
    {
        return $this->createdTime;
    }

    public function getCustomMetadata(): mixed
    {
        return $this->customMetadata;
    }

    public function getDeletionTime(): ?string
    {
        return $this->deletionTime;
    }

    public function getDestroyed(): ?bool
    {
        return $this->destroyed;
    }

    public function getVersion(): ?int
    {
        return $this->version;
    }

    public static function fromJson(stdClass $data): self
    {
        return new self(
            $data->created_time ?? null,
            $data->custom_metadata ?? null,
            $data->deletion_time ?? null,
            $data->destroyed ?? null,
            $data->version ?? null
        );
    }
}