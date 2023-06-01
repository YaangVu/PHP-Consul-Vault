# Laravel Consul

`Laravel Consul` help you load config from `Consul` server

## Install

`composer require yaangvu/php-consul-vault`

### For Laravel

Register service in `providers` array in `config/app.php`

```
YaangVu\PhpConsulVault\Laravel\Provider\ConsulProvider::class
```

Publish consul configuration file

```
php artisan vendor:publish --provider="YaangVu\PhpConsulVault\Laravel\Provider\ConsulProvider"
```

### For Lumen

Register service in `app/Providers/AppServiceProvider.php`

```php
 public function register()
    {
        $this->app->register(\YaangVu\Consul\ConsulProvider::class);
    }
```

Publish consul configuration file

``` shell
cp vendor/yaangvu/php-consul-vault/src/Laravel/Config/consul.php config/consul.php
```

### Config

Append `.env` file with these configurations:

```dotenv
CONSUL_ENABLE=true
CONSUL_URI=${CONSUL_URI}
CONSUL_TOKEN=${CONSUL_TOKEN}
CONSUL_SCHEME=${CONSUL_SCHEME}
CONSUL_DC=${CONSUL_DC}
CONSUL_PATH=${CONSUL_PATH}
CONSUL_RECURSIVE=true
```

Add any Key Folder Consul you want to be loaded in `config/consul.php`

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