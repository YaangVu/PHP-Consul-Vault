# PHP + Laravel Vault

`Php + Laravel Vault` help you load config from `Vault` server and wrapper for Laravel

## Install

`composer require yaangvu/php-consul-vault`

### For Laravel

Register service in `providers` array in `config/app.php`

```
Yaangvu\PhpConsulVault\Laravel\Provider\VaultProvider::class
```

Publish consul configuration file

```
php artisan vendor:publish --provider="YaangVu\PhpConsulVault\Laravel\Provider\VaultProvider"
```

### For Lumen

Register service in `app/Providers/AppServiceProvider.php`

```php
 public function register()
    {
        $this->app->register(\YaangVu\PhpConsulVault\Laravel\Provider\VaultProvider::class);
    }
```

Publish consul configuration file

``` shell
cp vendor/yaangvu/php-consul-vault/src/Laravel/Config/vault.php config/vault.php
```

### Config

Append `.env` file with these configurations:

```dotenv
VAULT_ENABLE=true
VAULT_HOST=${VAULT_HOST}
VAULT_TOKEN=${VAULT_TOKEN}
```

Add any Vault Path you want to be loaded in `config/vault.php`

```php
'paths' => [
    // 'secret/foo',
    // 'secret/bar'
],
```

### Get env from Consul

```shell
php artisan yaangvu:vault:kv
```

