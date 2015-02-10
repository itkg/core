<?php

define ('TEST_BASE_DIR', __DIR__);
ini_set('default_socket_timeout', 1); // Reduce Redis connect timeout
if (!file_exists($autoloadFile = __DIR__.'/../vendor/autoload.php')) {
    throw new \LogicException('Could not find autoload.php in vendor/. Did you run "composer install --dev"?');
}

require $autoloadFile;