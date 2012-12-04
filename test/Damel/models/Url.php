<?php

define('DOCUMENT_ROOT', __DIR__ . '/../../..');
require DOCUMENT_ROOT . '/app/config/constants.php';
require DOCUMENT_ROOT . '/src/Damel/models/Url.php';


class Url extends \PHPUnit_Framework_TestCase {


	public function testConnection() {
		$this->assertInstanceOf('\Damel\models\Url', new \Damel\models\Url());
	}

	/**
	 * @depends testConnection
	 */
	public function testAddUrl() {
		$url = new \Damel\models\Url();
		$result = $url->addUrl('http://example.org');
		$this->assertStringMatchesFormat('%s', $result);
	}

	/**
	 * @depends testAddUrl
	 */
	public function testFalseAddUrl() {
		$url = new \Damel\models\Url();
		$result = $url->addUrl('bullshit');
		$this->assertArrayHasKey('url', $result);
	}

	/**
	 * @depends testAddUrl
	 */
	public function testCustomAddUrl() {
		$url = new \Damel\models\Url();
		$result = $url->addUrl('http://example.com', 'custom');
		$this->assertEquals('custom', $result);
	}

	/**
	 * @depends testConnection
	 */
	public function testRetrieveUrlByCode() {
		$url = new \Damel\models\Url();
		$result = $url->retrieveUrlByCode('custom');
		$this->assertStringMatchesFormat('%s', $result);
	}

	/**
	 * @depends testConnection
	 */
	public function testRetrieveCodeByUrl() {
		$url = new \Damel\models\Url();
		$result = $url->retrieveCodeByUrl('http://example.org');
		$this->assertStringMatchesFormat('%s', $result);
	}

	/**
	 * @depends testConnection
	 */
	public function testRetrieveAll() {
		$url = new \Damel\models\Url();
		$result = $url->retrieveAll();
		$this->assertNotEmpty($result);
	}

	/**
	 * @depends testRetrieveAll
	 */
	public function testRetrievePage() {
		$url = new \Damel\models\Url();
		$result = $url->retrievePage();

		$this->assertNotEmpty($result);
		$this->assertArrayHasKey('entries', $result);
		$this->assertArrayHasKey('currentPage', $result);
		$this->assertArrayHasKey('numPages', $result);
		$this->assertEquals(1, $result['currentPage']);
	}

}
