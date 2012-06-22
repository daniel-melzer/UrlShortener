<?php
error_reporting(E_ALL);
ini_set('display_errors', true);

require __DIR__ . '/../vendor/flight/Flight.php';
require __DIR__ . '/../src/routes.php';

Flight::set('flight.views.path', __DIR__ . '/../resource/template');

Flight::start();