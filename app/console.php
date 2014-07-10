<?php

use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Application;

$input = new ArgvInput();

$application = new Application();

$params = array(
    'dbname' => '(DESCRIPTION = (ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = 10.255.252.248)(PORT = 1521)) )
             (CONNECT_DATA = (SERVER = DEDICATED) (SERVICE_NAME = orcl) ) )',
    'user'   => 'mycanal2',
    'password' => 'mycanal2',
    'host' => '',
    'driver' => 'oci8'
);

$config = new \Doctrine\DBAL\Configuration();

$connection = \Doctrine\DBAL\DriverManager::getConnection($params, $config);
$setup = new \Itkg\Core\Command\Script\Setup(
    new \Itkg\Core\Command\Script\Runner($connection),
    new \Itkg\Core\Command\Script\Loader($connection),
    new \Itkg\Core\Command\Script\Migration\Factory()
);
$application->addCommands(array(
    new \Itkg\Core\Command\ExecuteScriptCommand('itkg-core:script:execute', $setup),
    new \Itkg\Core\Command\GenerateScriptCommand('itkg-core:script:generate')
));

$application->run($input);