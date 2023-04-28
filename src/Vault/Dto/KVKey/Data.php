<?php

namespace Yaangvu\PhpConsulVault\Vault\Dto\KVKey;

use stdClass;

final readonly class Data
{
    /**
     * @param string[] $keys
     */
    public function __construct(private array $keys)
    {
    }

    public static function fromJson(stdClass $data): self
    {
        return new self(
            $data->keys
        );
    }

    /**
     * @return string[]
     */
    public function getKeys(): array
    {
        return $this->keys;
    }
}