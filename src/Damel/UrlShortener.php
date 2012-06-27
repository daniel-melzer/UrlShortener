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
		if(!filter_var($url, FILTER_VALIDATE_URL)) {
			throw new \InvalidArgumentException('URL invalid');
		}

		if(!is_string($code) && !empty($code)) {
			$statement = $this->con->prepare('
					SELECT
						*
					FROM
						`url`
					WHERE
						`code` LIKE :code');
			$statement->bindValue(':code', $code);
			$result = $statement->execute();
			if(!empty($result)) {
				throw new \InvalidArgumentException('Code already in use');
			}
		} else {
			$code = $this->generateCode();
		}

		return $this->storeUrl($url, $code);
	}

	/**
	 * Inserts a new URL in the database.
	 *
	 * @param string $url
	 * @param string $code
	 * @return bool|string
	 */
	private function storeUrl($url, $code) {
		$statement = $this->con->prepare('
				INSERT INTO
					`url`
				VALUES(:code, :url, :created_at)');
		$statement->bindValue(':code', $code);
		$statement->bindValue(':url', $url);
		$statement->bindValue(':created_at', date('Y-m-d H:i:s'));

		if(!$statement->execute()) {
			return false;
		}

		return $code;
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
