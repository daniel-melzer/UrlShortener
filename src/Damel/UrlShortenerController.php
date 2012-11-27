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

	/**
	 * Tries to save a new entry.
	 *
	 * @param \Flight\net\Request $request
	 * @return null
	 */
	public function saveAction(\Flight\net\Request $request) {
		$result = $this->model->addUrl($request->data->url, $request->data->code);

		if(is_string($result)) {
			$shortUrl = (isset($_SERVER['SERVER_PORT']) && (80 != $_SERVER['SERVER_PORT']) ?
					'https' : 'http') . '://' . $_SERVER['SERVER_NAME'] . '/' . $result;
			$template = 'url';
			$data = array('shortUrl' => $shortUrl);
		} else {
			$template = 'index';
			$data = array(
					'url' => $request->data->url,
					'code' => $request->data->code,
					'errors' => $result
			);
		}

		$this->render($template, $data);
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
