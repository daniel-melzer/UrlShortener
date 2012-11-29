<?php
spl_autoload_register(function($className) {
	$paths = array(
			VENDOR_DIR . '/flight',
			SRC_DIR
	);

	$className = ltrim($className, "\\");
	foreach($paths as $path) {
		preg_match('/^(.+)?([^\\\\]+)$/U', $className, $match);
		$className = str_replace('\\', '/', $match[1])
				. str_replace(array('\\', '_'), '/', $match[2]);

		$file = $path . DIRECTORY_SEPARATOR . $className . '.php';
		if(file_exists($file)) {
			require $file;
			break;
		}
	}
});
