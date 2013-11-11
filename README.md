DIC & Extension manager library
========================

## features
* DIC
* Extension manager

## Installation

### Installation by Composer

If you use composer, add ExtraFormBundle bundle as a dependency to the composer.json of your application

```php
    "require": {
        ...
        "itkg/core": "dev-master"
        ...
    },

```

If you use itkg/core DIC, you can do :

```php
// app/AppKernel.php
<?php
    // init core
    $core = new Itkg\Core('../../var/cache/itkg_cache.php', true);

    // Add some extensions
    $core->registerExtension(new \Itkg\Consumer\DependencyInjection\ItkgCacheExtension());
    / ..
    // Load DIC
    $core->load();

```

## Usage

* Extensions
