<?php

namespace Damel;


class UrlShortenerController {


	/**
	 * @var \Damel\UrlShortener
	 */
	private $model = null;


	/**
	 * constructor.
	 *
	 * @param UrlShortener $model
	 * @return UrlShortenerController
	 */
	public function __construct(\Damel\UrlShortener $model) {
		$this->model = $model;
	}

	/**
	 * Renders index page.
	 *
	 * @return null
	 */
	public function indexAction() {
		$this->render('index', array());
	}

	public function saveAction() {
		$request = \Flight::request();
		$shortener = \Flight::shortener();
		$result = $shortener->addUrl($request->data->url, $request->data->code);

		if(is_string($result)) {
			$shortUrl = (isset($_SERVER['SERVER_PORT']) && (80 != $_SERVER['SERVER_PORT']) ?
					'https' : 'http') . '://' . $_SERVER['SERVER_NAME'] . '/' . $result;
			\Flight::render('url', array(
					'shortUrl' => $shortUrl
			), 'content');
		} else {
			\Flight::render('index', array(
				'url' => $request->data->url,
				'code' => $request->data->code,
				'errors' => $result
			), 'content');
		}
		\Flight::render('layout', array());
	}

	public function listAction($page) {
		$entriesPerPage = 25;
		$page = \Flight::shortener()->retrievePage($page, $entriesPerPage);
		\Flight::render('list', $page, 'content');
		\Flight::render('layout', array());
	}

	public function redirectAction($code) {
		$shortener = \Flight::shortener();
		$url = $shortener->retrieveUrlByCode($code);
		if(!empty($url)) {
			\Flight::redirect($url);
		} else {
			\Flight::notFound();
		}
	}

	/**
	 * Render page content.
	 *
	 * Calls \Flight::render() with given template first, then calls it
	 * again for the layout template.
	 *
	 * @param string $template
	 * @param array $data
	 * @return null
	 */
	private function render($template, array $data) {
		\Flight::render($template, $data, 'content');
		\Flight::render('layout', array());
	}

}
