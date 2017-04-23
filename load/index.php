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
<?php include_once "../qslconf.php"; ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<!-- <meta name="description" content="">
		<meta name="author" content="">
		<link rel="icon" href="../favicon.ico"> -->

		<title><?php echo $club_call; ?> QSL Print System ADIF Loader</title>

		<!-- Bootstrap core CSS -->
		<link href="../css/bootstrap.min.css" rel="stylesheet">

		<!-- Custom styles for this template -->
		<link href="../css/qsl.css" rel="stylesheet">


	</head>

	<body>

		<nav class="navbar navbar-inverse navbar-fixed-top">
			<div class="container">
			<p class="qsl-head"><?php echo $club_call; ?> QSL Print System ADIF Loader</p>
			</div>
		</nav>
		
		<div class="container">
			<h1><?php echo $club_call; ?> ADIF Loader</h1>
		</div>
		
		<div class="container">
			<div class="row">
				<div class="col-md-8">
					<div class="qsl">
						<p>To use the loader enter your call sign, the load key provided by 
						your club QSL manager in the <i>Load Key</i> box, select your ADIF file for 
						uploading, and then click the upload button. The loader supports ADIF 2.0
						and the ADIF v3.0 non-XML formats.</p>
						<form method="post" action="qsladifloader.php" enctype="multipart/form-data">
						<p><b>Callsign:</b> <input type="text" size="30" name="csign" /> <i>Note: Do not use any trailing /M, /P, etc.</i></p>
						<p><b>Load Key:</b> <input type="text" size="30" name="loadkey" /></p>		
						<p><input type="file" name="adiffile" id="adiffile" /></p>
						<p><input type="submit" value="Upload ADIF" name="submit"></p>
						</form>
					</div>
				</div>
				<div class="col-md-4">
					<div class="qsl">
						<div class="alert alert-warning">
						<p><strong>Note about HamLog:</strong> This system may not work
						with ADIF files from HamLog depending on the options 
						chosen during the export. You must provide the fields for 
						Callsign, QSO Date, Time On, Operator, and Freq or Band. If
						you are having problems uploading a HamLog file, import your 
						HamLog ADIF into your normal logging software, and
						then re-export the QSOs.<p>
						</div>
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
		<script src="../js/jquery.min.js"></script>
		<script>window.jQuery || document.write('<script src="../js/jquery.min.js"><\/script>')</script>
		<script src="../js/bootstrap.min.js"></script>
	</body>
</html>
