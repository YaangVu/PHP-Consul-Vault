<?php

namespace Yaangvu\PhpConsulVault\Vault\Dto\KV2;

use stdClass;

final class SecretDataDto
{
    public function __construct(private readonly ?object $data, private readonly ?SecretMetadataDto $metadata)
    {
    }

    public function getData(): ?object
    {
        return $this->data;
    }

    public function getMetadata(): ?SecretMetadataDto
    {
        return $this->metadata;
    }

    public static function fromJson(stdClass $data): self
    {
        return new self(
            $data->data ?? null,
            ($data->metadata ?? null) !== null
                ? SecretMetadataDto::fromJson($data->metadata)
                : null
        );
    }
}