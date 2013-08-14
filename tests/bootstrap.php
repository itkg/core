<?php

ini_set('display_errors', 'on');

$loader = require_once('vendor/autoload.php');

$loader->add('Lemon', array(
	__DIR__.'/../src',
	__DIR__.'/../tests'
));

$loader->add('LemonTest', array(
	__DIR__.'/../tests'
));