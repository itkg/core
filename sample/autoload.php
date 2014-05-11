<?php

ini_set('display_errors', 1);
session_start();
$loader = require_once '../vendor/autoload.php';


// Itkg_cache.php contains config && debug is actived
$loader = new Itkg\Config\Loader('../var/cache/itkg_cache.php', true);

// Add extensions

// Load config

$loader->load();