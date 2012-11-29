<?php
//404 page.
\Flight::map('notFound', function() {
	\Flight::shortener()->notFoundAction();
});

//Index page.
\Flight::route('GET /', function() {
	\Flight::shortener()->indexAction();
});

\Flight::route('POST /', function() {
	\Flight::shortener()->saveAction(\Flight::request());
});

//List pages.
Flight::route('GET /list/@page:[0-9]+', function($page) {
	\Flight::shortener()->listAction($page);
});

//Redirect URL.
Flight::route('GET /@code:[a-zA-Z0-9]{4,}', function($code) {
	\Flight::shortener()->redirectAction($code);
});
