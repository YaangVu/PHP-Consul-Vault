<?php

namespace YaangVu\PhpConsulVault\Vault\Enum;

enum AuthMethodEnum: string
{
    case TOKEN = 'token';
    case USERPASS = 'userpass';
}
