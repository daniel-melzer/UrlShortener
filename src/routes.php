<?php
//Index page.
Flight::route('GET /', function() {
	Flight::render('index', array(), 'content');
	Flight::render('layout', array());
});

Flight::route('POST /', function() {
	$error = '';
	$request = Flight::request();
	$shortener = Flight::shortener();
	try {
		$result = $shortener->addUrl(Flight::request()->data->url, Flight::request()->data->code);
	} catch(\InvalidArgumentException $e) {
		$error = $e->getMessage();
	}

	if($result) {
		$shortUrl = (isset($_SERVER['SERVER_PORT']) && (80 != $_SERVER['SERVER_PORT']) ?
				'https' : 'http') . '://' . $_SERVER['SERVER_NAME'] . '/' . $result;
		Flight::render('url', array(
			'shortUrl' => $shortUrl
		), 'content');
	} else {
		Flight::render('index', array(
			'url' => Flight::request()->data->url,
			'code' => Flight::request()->data->code,
			'error' => $error
		), 'content');
	}
	Flight::render('layout', array());
});