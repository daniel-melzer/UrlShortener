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

//List pages.
Flight::route('GET /list/@page:[0-9]+', function($page) {
	$shortener = Flight::shortener();
	$list = $shortener->retrieveAll();

	$elements = 25;
	$offset = $elements * ($page - 1);
	$pageList = array_slice($list, $offset, $elements);

	Flight::render('list', array(
			'list' => $pageList,
			'currentPage' => $page,
			'numPages' => ceil(count($list) / $elements)
	), 'content');
	Flight::render('layout', array());
});

//Redirect URL.
Flight::route('GET /@code:[a-zA-Z0-9]{4,}', function($code) {
	$shortener = Flight::shortener();
	try {
		$url = $shortener->retrieveUrlByCode($code);
		if($url) {
			Flight::redirect($url);
		}
	} catch(\RuntimeException $e) {
		Flight::halt(404, 'URL not found');
	}
});