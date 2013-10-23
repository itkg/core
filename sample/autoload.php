<?php

ini_set('display_errors', 1);
session_start();
$loader = require_once '../vendor/autoload.php';


// Itkg_cache.php contains config && debug is actived
$itkg = new Itkg('../var/cache/itkg_cache.php', true);

// Add extensions

// Load config

$itkg->load();