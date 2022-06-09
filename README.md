# laravel-check-version

A Laravel Lib to check app version from app store and play store

## Installation

Add in composer.json:

```php
"repositories": [
    {
        "type": "vcs",
        "url": "https://git.codificar.com.br/laravel-libs/laravel-check-version.git"
    }
]
```

```php
require:{
        "codificar/check-version": "0.0.1",
}
```

```php
"autoload": {
    "psr-4": {
        "Codificar\\CheckVersion\\": "vendor/codificar/check-version/src/"
    },
}
```

Update project dependencies:

```shell
$ composer update
```

Register the service provider in `config/app.php`:

```php
'providers' => [
  /*
   * Package Service Providers...
   */
  Codificar\CheckVersion\CheckVersionProvider::class,
],
```
