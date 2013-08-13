<?php

/*
	Simple code to initialize DIC 

 */
ini_set('display_errors', 1);
require_once '../vendor/autoload.php';

use Lemon;

// lemon_cache.php contains config && debug is actived
$lemon = new Lemon('/var/cache/lemon_cache.php', true);

// Add some extension
$lemon->registerExtension(new \Lemon\Package\LemonPackageExtension());

// Load config
$lemon->load();

// Accès à une donnée
echo Lemon::get('toto');