Commands
========

# Installation

If you do not have a console.php, you can create one like this :

```php

use Itkg\Core\Command\Provider\ServiceCommandProvider;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Application;

$container['doctrine.connection'] = $container->share(function($container) {

    $params = array(
        'dbname' => YOUR_BD_NAME,
        'user'   => YOUR_BD_USER,
        'password' => YOUR_BD_PASSWD,
        'host' => YOUR_DB_HOST,
        'driver' => YOUR_DATABASE_DRIVER
    );

    $config = new \Doctrine\DBAL\Configuration();

    return \Doctrine\DBAL\DriverManager::getConnection($params, $config);
});

// Register command provider
$container->register(new ServiceCommandProvider());
$application = new Application();

$application->addCommands(
    array(
        $container['itkg-core.command.database_update'], // Add database update command
    )
);

$application->run(new ArgvInput());

```


# Usage

* [Database update](https://github.com/itkg/core/tree/master/docs/command/database_update.md)
* [Database list](https://github.com/itkg/core/tree/master/docs/command/database_list.md)
