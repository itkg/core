DIC & Extension management library
========================

## features
* DIC
* Extension manager

## Installation

### Installation by Composer

If you use composer, add library as a dependency to the composer.json of your application

```php
    "require": {
        ...
        "itkg/config": "dev-master"
        ...
    },

```

If you use itkg/config DIC, you can do :

```php
<?php
    // init core
    $loader = new Itkg\Config\Loader('../../var/cache/itkg_cache.php', true);

    // Add some extensions
    $loader->registerExtension(new \Itkg\Cache\DependencyInjection\ItkgCacheExtension());
    / ..
    // Load DIC
    $loader->load();

```

## Usage

* Extensions
