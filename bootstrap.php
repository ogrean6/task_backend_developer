<?php
// bootstrap.php

// Composer autoloader
require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Set default timezone (optional)
date_default_timezone_set('UTC');

// Custom error reporting (optional)
error_reporting(E_ALL);
ini_set('display_errors', '1');