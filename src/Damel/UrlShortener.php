<?php

namespace Damel;


class UrlShortener {


	const DATABASE_NAME = 'resource/sqlite/urlshortener.sqlite';


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
		$dsn = __DIR__ . '/../../' . self::DATABASE_NAME;
		$this->con = new \SQLite3($dsn, SQLITE3_OPEN_READWRITE);
	}

	/**
	 * Adds an entry and returns the short code for an URL.
	 *
	 * @param string $url
	 * @param null|string $code
	 * @return bool|string
	 * @throws \InvalidArgumentException
	 */
	public function addUrl($url, $code = null) {
		$return = false;
		if(!filter_var($url, FILTER_VALIDATE_URL)) {
			throw new \InvalidArgumentException('URL invalid');
		}

		if(is_string($code) && !empty($code)) {
			if(4 > strlen($code)) {
				throw new \InvalidArgumentException('Code must be at least 4 characters long');
			}

			$statement = $this->con->prepare('
					SELECT
						*
					FROM
						`url`
					WHERE
						`code` LIKE :code');
			$statement->bindValue(':code', $code);
			$result = $statement->execute()->fetchArray();
			if(!empty($result)) {
				throw new \InvalidArgumentException('Code already in use');
			}

			$return = $this->storeUrl($url, $code, true);
		} else {
			$return = $this->retrieveCodeByUrl($url);
			if(!$return) {
				$code = $this->generateCode();
				$return = $this->storeUrl($url, $code);
			}
		}

		return $return;
	}

	/**
	 * Retrieves the long URL from the database.
	 *
	 * @param string $code
	 * @return string
	 * @throws \RuntimeException
	 */
	public function retrieveUrlByCode($code) {
		$statement = $this->con->prepare('
				SELECT
					*
				FROM
					`url`
				WHERE
					`code` LIKE :code');
		$statement->bindValue(':code', $code);
		$result = $statement->execute()->fetchArray();
		if(empty($result)) {
			throw new \RuntimeException('Code not found');
		}

		return $result['url'];
	}

	/**
	 * Retrieves the non-custom code from the database.
	 *
	 * @param string $url
	 * @return string
	 * @throws \RuntimeException
	 */
	public function retrieveCodeByUrl($url) {
		$statement = $this->con->prepare('
				SELECT
					*
				FROM
					`url`
				WHERE
					`url` LIKE :url
					AND `custom` = 0');
		$statement->bindValue(':url', $this->con->escapeString($url));
		$result = $statement->execute()->fetchArray();
		if(empty($result)) {
			throw new \RuntimeException('URL not found');
		}

		return $result['code'];
	}

	/**
	 * Retrieves all entries from the database.
	 *
	 * @return array
	 * @throws \RuntimeException
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
		if(!($result instanceof \SQLite3Result)) {
			throw new \RuntimeException('No URLs found');
		}

		while($row = $result->fetchArray()) {
			$return[] = $row;
		}

		return $return;
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
		$in = rand();
		$padUp = 4;
		$index = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$base = strlen($index);

		if(is_numeric($padUp)) {
			$padUp--;
			if(0 < $padUp) {
				$in += pow($base, $padUp);
			}
		}

		$out = "";
		for($t = floor(log($in, $base)); $t >= 0; $t--) {
			$bcp = bcpow($base, $t);
			$a = floor($in / $bcp) % $base;
			$out = $out . substr($index, $a, 1);
			$in = $in - ($a * $bcp);
		}
		$out = strrev($out);

		return $out;
	}

}
