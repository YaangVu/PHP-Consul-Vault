<?php

namespace YaangVu\PhpConsulVault\Vault\Dto\DbListConnections;

use stdClass;

class Data
{
    /**
     * @param string[] $keys
     */
    public function __construct(private readonly array $keys)
    {
    }

    /**
     * @return string[]
     */
    public function getKeys(): array
    {
        return $this->keys;
    }

    public static function fromJson(stdClass $data): self
    {
        return new self(
            $data->keys
        );
    }
}