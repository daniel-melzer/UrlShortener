<?php
//Index page.
Flight::route('/', function() {
	Flight::render('index', array(), 'content');
	Flight::render('layout', array());
});