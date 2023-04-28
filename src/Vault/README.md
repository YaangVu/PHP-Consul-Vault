# PHP + Laravel Vault

`Php + Laravel Vault` help you load config from `Vault` server and wrapper for Laravel

## Install

`composer require yaangvu/php-consul-vault`

### For Laravel

Register service in `providers` array in `config/app.php`

```
YaangVu\Consul\VaultProvider::class
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
cp vendor/yaangvu/php-consul-vault/src/Laravel/Config/consul.php config/vault.php
```

### Config

Append `.env` file with these configurations:

```dotenv
VAULT_ENABLE=true
VAULT_URI=${VAULT_URI}
VAULT_TOKEN=${VAULT_TOKEN}
VAULT_SCHEME=${VAULT_SCHEME}
VAULT_DC=${VAULT_DC}
VAULT_PATH=${VAULT_PATH}
VAULT_RECURSIVE=true
```

Add any Key Folder Consul you want to be loaded

```php
'paths' => [
    // 'secret/foo',
    // 'secret/bar'
],
```

### Get env from Consul
```shell
php artisan yaangvu:consul:kv
```

