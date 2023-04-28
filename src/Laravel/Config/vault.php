<?php

return [
    'host'        => env('VAULT_HOST', 'localhost:8200'),
    'enable'      => env('VAULT_ENABLE', true),
    'auth_method' => env('VAULT_AUTH_METHOD', true),
    'token'       => env('VAULT_TOKEN', null),
    'username'    => env('VAULT_USERNAME', null),
    'password'    => env('VAULT_PASSWORD', null),
    'paths'        => [],
    'api_version' => 'v1',
    'kv_version'  => env('VAULT_KV_VERSION', 2),
];