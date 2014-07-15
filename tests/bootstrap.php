<?php

$baseDir = dirname(__DIR__.'/../../');

if (!is_file($autoloadFile = __DIR__.'/../' . 'vendor/autoload.php')) {
    throw new \LogicException('Could not find autoload.php in vendor/. Did you run "composer install --dev"?');
}

$loader = require $autoloadFile;
$loader->set('Itkg\\Core', array($baseDir.'/src/', $baseDir.'/tests/'));

$loader->register();