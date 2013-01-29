<?php
require DOCUMENT_ROOT . '/app/config/constants.php';
require VENDOR_DIR . '/autoload.php';

//Register classes.
\Flight::register('shortener', '\Damel\controller\UrlShortener', array(new \Damel\models\Url()));

//Mappings.
\Flight::map('loadConfig', function($configFile) {
	if(!is_readable($configFile)) {
		throw new RuntimeException('Config not readable.');
	}

	foreach(parse_ini_file($configFile, true) as $section => $values) {
		foreach($values as $key => $value) {
			\Flight::set($section . '.' . $key, $value);
		}
	}
});
\Flight::map('notFound', function() {
	\Flight::shortener()->notFoundAction();
});

require CONFIG_DIR . '/routes.php';