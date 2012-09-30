<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>UrlShortener</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">

		<!-- Le styles -->
		<link href="/assets/css/bootstrap.css" rel="stylesheet">
		<style>
			body {
				padding-top: 60px;
			}
		</style>
		<link href="/assets/css/bootstrap-responsive.css" rel="stylesheet">

		<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->

		<!-- Le fav and touch icons -->
		<link rel="shortcut icon" href="/assets/ico/favicon.ico">
		<link rel="apple-touch-icon-precomposed" sizes="144x144" href="/assets/ico/apple-touch-icon-144-precomposed.png">
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="/assets/ico/apple-touch-icon-114-precomposed.png">
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="/assets/ico/apple-touch-icon-72-precomposed.png">
		<link rel="apple-touch-icon-precomposed" href="/assets/ico/apple-touch-icon-57-precomposed.png">
	</head>

	<body>
		<div class="navbar navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container offset2">
					<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</a>
					<a class="brand" href="/">UrlShortener</a>
					<div class="nav-collapse">
						<ul class="nav">
							<li<?php if(false === strpos($_SERVER['REQUEST_URI'], 'list')): ?> class="active"<?php endif; ?>><a href="/">Home</a></li>
							<li<?php if(false !== strpos($_SERVER['REQUEST_URI'], 'list')): ?> class="active"<?php endif; ?>><a href="/list/1">List</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>

		<div class="container">
			<?php echo $content; ?>
		</div>
		<script src="/assets/js/jquery.js"></script>
		<script src="/assets/js/bootstrap.min.js"></script>
	</body>
</html>
