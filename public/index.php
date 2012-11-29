<?php
error_reporting(E_ALL);
ini_set('display_errors', true);
define('DOCUMENT_ROOT', __DIR__ . '/..');

require DOCUMENT_ROOT . '/app/app.php';

\Flight::loadConfig(CONFIG_DIR . '/config.ini');
\Flight::start();