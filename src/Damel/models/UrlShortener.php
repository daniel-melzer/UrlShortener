<?php

namespace Damel;


class UrlShortener {


	const DATABASE_NAME = 'app/resource/sqlite/urlshortener.sqlite';


	/**
	 * @var \SQLite3
	 */
	private $con;


	/**
	 * Establishes a connection to the SQLite database.
	 *
	 * @return UrlShortener
	 */
	public function __construct() {
		$dsn = DOCUMENT_ROOT . '/' . self::DATABASE_NAME;
		$this->con = new \SQLite3($dsn, SQLITE3_OPEN_READWRITE);
	}

	/**
	 * Adds an entry and returns the short code for an URL.
	 *
	 * @param string $url
	 * @param null|string $code
	 * @return array|string
	 */
	public function addUrl($url, $code = null) {
		$return = $this->validateForm($url, $code);

		if(true === $return) {
			if(is_string($code) && !empty($code)) {
				$return = $this->storeUrl($url, $code, true);
			} else {
				$return = $this->retrieveCodeByUrl($url);
				if('' == $return) {
					$code = $this->generateCode();
					$return = $this->storeUrl($url, $code);
				}
			}
		}

		return $return;
	}

	/**
	 * Retrieves the long URL from the database.
	 *
	 * @param string $code
	 * @return string
	 */
	public function retrieveUrlByCode($code) {
		$url = '';
		$statement = $this->con->prepare('
				SELECT
					`url`
				FROM
					`url`
				WHERE
					`code` LIKE :code');
		$statement->bindValue(':code', $this->con->escapeString($code));
		$result = $statement->execute()->fetchArray();
		if(!empty($result)) {
			$url = $result['url'];
		}

		return $url;
	}

	/**
	 * Retrieves the non-custom code from the database.
	 *
	 * @param string $url
	 * @return string
	 */
	public function retrieveCodeByUrl($url) {
		$code = '';
		$statement = $this->con->prepare('
				SELECT
					`code`
				FROM
					`url`
				WHERE
					`url` LIKE :url
					AND `custom` = 0');
		$statement->bindValue(':url', $this->con->escapeString($url));
		$result = $statement->execute()->fetchArray();
		if(!empty($result)) {
			$code = $result['code'];
		}

		return $code;
	}

	/**
	 * Retrieves all entries from the database.
	 *
	 * @return array
	 */
	public function retrieveAll() {
		$return = array();
		$result = $this->con->query('
				SELECT
					*
				FROM
					`url`
				ORDER BY
					`created_at` DESC');
		if($result instanceof \SQLite3Result) {
			while($row = $result->fetchArray()) {
				$return[] = $row;
			}
		}

		return $return;
	}

	/**
	 * Retrieve all entries from database within a page.
	 *
	 * @param int $page
	 * @param int $elements Number of entries per page
	 * @return array
	 */
	public function retrievePage($page = 1, $elements = 25) {
		$numEntries = $this->countEntries();
		$numPages = ceil($numEntries / $elements);
		if(1 > $page || 0 == $numPages) {
			$page = 1;
		} elseif(0 < $numPages && $numPages < $page) {
			$page = $numPages;
		}
		$offset = $elements * ($page - 1);
		$page = array(
				'currentPage' => $page,
				'numPages' => $numPages,
				'entries' => array()
		);
		$query = $this->con->prepare('
				SELECT
					*
				FROM
					`url`
				ORDER BY
					`created_at` DESC
				LIMIT
					:offset, :limit');
		$query->bindValue(':offset', $offset);
		$query->bindValue(':limit', $elements);

		if($result = $query->execute()) {
			if($result instanceof \SQLite3Result) {
				while($row = $result->fetchArray()) {
					$page['entries'][] = $row;
				}
			}
		}

		return $page;
	}
  
	/**
	 * Retrieve the number of all entries in the database.
	 *
	 * @return int
	 */
  	private function countEntries() {
    	$count = $this->con->querySingle('
        		SELECT COUNT
          			(`code`)
        		FROM
          			`url`');

		return (int)$count;
	}

	/**
	 * Inserts a new URL in the database.
	 *
	 * @param string $url
	 * @param string $code
	 * @param bool $isCustom
	 * @return bool|string
	 */
	private function storeUrl($url, $code, $isCustom = false) {
		$return = $code;
		$statement = $this->con->prepare('
				INSERT INTO
					`url`
				VALUES(:code, :url, :created_at, :custom)');
		$statement->bindValue(':code', $this->con->escapeString($code));
		$statement->bindValue(':url', $this->con->escapeString($url));
		$statement->bindValue(':created_at', $this->con->escapeString(date('Y-m-d H:i:s')));
		$statement->bindValue(':custom', (int)$isCustom);

		if(!$statement->execute()) {
			$return = false;
		}

		return $return;
	}

	/**
	 * Generates a random code for the URL.
	 *
	 * @return string
	 */
	private function generateCode() {
		$random = rand();
		$code = '';
		$minLength = 4;
		$index = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$base = strlen($index);

		$minLength--;
		if(0 < $minLength) {
			$random += pow($base, $minLength);
		}

		for($t = floor(log($random, $base)); 0 <= $t; $t--) {
			$bcp = bcpow($base, $t);
			$start = floor($random / $bcp) % $base;
			$code = $code . substr($index, $start, 1);
			$random = $random - ($start * $bcp);
		}
		$code = strrev($code);

		return $code;
	}

	/**
	 * Validates the form data.
	 *
	 * @param string $url
	 * @param string|null $code
	 * @return bool|array
	 */
	private function validateForm($url, $code) {
		$errors = array();

		if(empty($url) || !filter_var($url, FILTER_VALIDATE_URL)) {
			$errors['url'] = 'URL invalid';
		}

		if(is_string($code) && !empty($code)) {
			if(4 > strlen($code)) {
				$errors['code'] = 'Code must be at least 4 characters long';
			} else {
				$result = $this->retrieveUrlByCode($code);
				if('' != $result) {
					$errors['code'] = 'Code already in use';
				}
			}
		}

		return empty($errors) ?: $errors;
	}

}
