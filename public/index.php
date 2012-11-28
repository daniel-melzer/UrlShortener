<?php
error_reporting(E_ALL);
ini_set('display_errors', true);
define('DOCUMENT_ROOT', __DIR__ . '/..');

require DOCUMENT_ROOT . '/vendor/flight/Flight.php';
require DOCUMENT_ROOT . '/app/config/routes.php';
require DOCUMENT_ROOT . '/src/Damel/models/UrlShortener.php';
require DOCUMENT_ROOT . '/src/Damel/controller/UrlShortenerController.php';

Flight::set('flight.views.path', DOCUMENT_ROOT . '/app/resource/template');
Flight::register('shortener', '\Damel\UrlShortenerController', array(
		new \Damel\UrlShortener()
));

Flight::start();