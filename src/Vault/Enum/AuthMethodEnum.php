<?php

namespace Yaangvu\PhpConsulVault\Vault\Enum;

enum AuthMethodEnum: string
{
    case TOKEN = 'token';
    case USERPASS = 'userpass';
}
