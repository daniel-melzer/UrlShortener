<?php

namespace Damel\controller;


class UrlShortener {


	/**
	 * @var \Damel\UrlShortener
	 */
	private $model = null;


	/**
	 * Constructor.
	 *
	 * @param \Damel\models\Url $model
	 * @return Damel\controller\UrlShortener
	 */
	public function __construct(\Damel\models\Url $model) {
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
	 * Renders form if errors occured, else renders shortened URL.
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

	/**
	 * Fetch entries for given list page and renders it.
	 *
	 * @param int $page
	 * @return null
	 */
	public function listAction($page) {
		$entries = $this->model->retrievePage($page, \Flight::get('site.list_default_limit'));

		$this->render('list', $entries);
	}

	/**
	 * Tries to fetch an URL by its code and redirects.
	 *
	 * Triggers 404 if URL couldn't be found.
	 *
	 * @param string $code
	 * @return null
	 */
	public function redirectAction($code) {
		$url = $this->model->retrieveUrlByCode($code);

		if(!empty($url)) {
			\Flight::redirect($url);
		} else {
			\Flight::notFound();
		}
	}

	/**
	 * Renders 404 page.
	 *
	 * @return null
	 */
	public function notFoundAction() {
		$this->render('404', array());
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
