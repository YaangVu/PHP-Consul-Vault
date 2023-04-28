<?php

namespace Yaangvu\PhpConsulVault\Vault\KVKeyDataDto;

final class Data
{
    /**
     * @param string[] $keys
     */
    public function __construct(private readonly array $keys)
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