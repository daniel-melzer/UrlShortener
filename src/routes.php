<?php
//404 page.
Flight::map('notFound', function() {
	Flight::render('404', array(), 'content');
	Flight::render('layout', array());
});

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
				'errors' => $result
		), 'content');
	}
	Flight::render('layout', array());
});

//List pages.
Flight::route('GET /list/@page:[0-9]+', function($page) {
	$entriesPerPage = 25;
	$page = Flight::shortener()->retrievePage($page, $entriesPerPage);
	Flight::render('list', $page, 'content');
	Flight::render('layout', array());
});

//Redirect URL.
Flight::route('GET /@code:[a-zA-Z0-9]{4,}', function($code) {
	$shortener = Flight::shortener();
	$url = $shortener->retrieveUrlByCode($code);
	if(!empty($url)) {
		Flight::redirect($url);
	} else {
		Flight::notFound();
	}
});
