<?php

use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Application;

$input = new ArgvInput();

$application = new Application();
$application->addCommands(array(
    new \Itkg\Core\Command\ExecuteScriptCommand('itkg-core:script:execute'),
    new \Itkg\Core\Command\GenerateScriptCommand('itkg-core:script:generate')
));

$application->run($input);