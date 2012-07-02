<?php
//Index page.
Flight::route('GET /', function() {
	Flight::render('index', array(), 'content');
	Flight::render('layout', array());
});

Flight::route('POST /', function() {
	$request = Flight::request();
	$shortener = Flight::shortener();
	$result = $shortener->addUrl($request->data->url, $request->data->code);

	if(is_string($result)) {
		$shortUrl = (isset($_SERVER['SERVER_PORT']) && (80 != $_SERVER['SERVER_PORT']) ?
				'https' : 'http') . '://' . $_SERVER['SERVER_NAME'] . '/' . $result;
		Flight::render('url', array(
				'shortUrl' => $shortUrl
		), 'content');
	} else {
		Flight::render('index', array(
				'url' => $request->data->url,
				'code' => $request->data->code,
				'error' => $result
		), 'content');
	}
	Flight::render('layout', array());
});

//List pages.
Flight::route('GET /list/@page:[0-9]+', function($page) {
	$shortener = Flight::shortener();
	$list = $shortener->retrieveAll();
	$elements = 25;
	$numPages = ceil(count($list) / $elements);

	if(1 > $page || 0 == $numPages) {
		$page = 1;
	} elseif(0 < $numPages && $numPages < $page) {
		$page = $numPages;
	}

	$offset = $elements * ($page - 1);
	$pageList = array_slice($list, $offset, $elements);

	Flight::render('list', array(
			'list' => $pageList,
			'currentPage' => $page,
			'numPages' => $numPages
	), 'content');
	Flight::render('layout', array());
});

//Redirect URL.
Flight::route('GET /@code:[a-zA-Z0-9]{4,}', function($code) {
	$shortener = Flight::shortener();
	$url = $shortener->retrieveUrlByCode($code);
	if(!empty($url)) {
		Flight::redirect($url);
	} else {
		Flight::halt(404, 'URL not found');
	}
});