<?php
/*
Copyright 2017 Jason D. McCormick

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
*/
?>
<?php include_once("qslconf.php"); ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<!-- <meta name="description" content="">
		<meta name="author" content="">
		<link rel="icon" href="../../favicon.ico"> -->

		<title><?php echo $club_call; ?> QSL Print System</title>

		<!-- Bootstrap core CSS -->
		<link href="css/bootstrap.min.css" rel="stylesheet">

		<!-- Custom styles for this template -->
		<link href="css/qsl.css" rel="stylesheet">

	</head>

	<body>

		<nav class="navbar navbar-inverse navbar-fixed-top">
			<div class="container">
			<p class="qsl-head"><?php echo $club_call; ?> QSL Print System</p>
			</div>
		</nav>
		
		<div class="container">
		<h1><?php echo $club_name; ?> - <?php echo $club_call; ?></h1>
		</div>
		
		<div class="container">
			<div class="row">
				<div class="col-md-8">
					<div class="qsl">
						<p>Welcome to the <?php echo $club_name; ?> QSL printing system.
						This system allows you retrieve and print QSLs for QSOs with the
						club call <?php echo $club_call; ?>. This system will provide
						you with any QSL on record in the <i>current</i> QSL card or certificate
						used by the club. To begin, enter your callsign below and click Search for QSOs.</p>
						<hr>
						<form action="qslfetch.php" method="post">
						<p class="lead">Call Sign: <input type="text" name="call"></p>
						<input id="submit" type="submit" value="Search for QSOs" />
						</form>
					</div>
				</div>
				<div class="col-md-4">
					<div class="qsl">
						<?php print $qsl_page_note; ?>
					</div>
				</div>
			</div>
		</div>
		<div id="footer">
			<div class="container">
				<hr>
				<p class="text-muted">Site information &copy;&nbsp;<?php print date("Y"); ?>&nbsp;<?php print $club_name; ?><br/>
				Powered by <a href="https://github.com/jxmx/smooth-qsl" target="_blank">Smooth QSL</a></p>
			</div>
		</div>
		<script src="js/jquery.min.js"></script>
		<script>window.jQuery || document.write('<script src="js/jquery.min.js"><\/script>')</script>
		<script src="js/bootstrap.min.js"></script>
		</body>
</html>
