<?php

ini_set('display_errors', 'on');
//ini_set('error_reporting', E_ALL );
$loader = require_once('vendor/autoload.php');

$loader->add('Itkg\Config', array(
        __DIR__.'/../src',
        __DIR__.'/../tests'
    ));
