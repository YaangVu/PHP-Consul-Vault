<?php

namespace YaangVu\PhpConsulVault\Vault\Dto\SecretKeys;

use stdClass;

final class KeysDataDto
{
    /**
     * @param string[]|null $keys
     */
    public function __construct(private readonly ?array $keys)
    {
    }

    /**
     * @return string[]|null
     */
    public function getKeys(): ?array
    {
        return $this->keys;
    }

    public static function fromJson(stdClass $data): self
    {
        return new self(
            $data->keys ?? null
        );
    }
}