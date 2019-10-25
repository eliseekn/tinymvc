<!DOCTYPE html>

<html lang="<?=WEB_LOCALE?>">
	<head>
		<title><?=$page_title?></title>

		<base href="<?=WEB_ROOT . "/"?>">

		<meta charset="utf-8">

		<style type="text/css">
			* {
				margin: 0;
				padding: 0;
				box-sizing: border-box;
			}

			div {
				text-align: center;
			}

			hr {
				width: 200px;
			}
		</style>
	</head>

	<body>
		<div>
			<h1>Error 404: <em>Page not found</em></h1>
			<hr>
			<p>The page you've requested doesn't exists on this server.</p>
			<p><a href="home">Go back home</a></p>
		</div>
	</body>
</html>
