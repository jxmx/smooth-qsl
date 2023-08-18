<?php
/*
Copyright 2017-2023 Jason D. McCormick

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
<?php
error_reporting(E_ALL);
$conn = new mysqli($db_server, $db_user, $db_pass, $db_db);
if( $conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}
$sql = "select min(str_to_date(qsodate, \"%Y-%m-%d\")) as qd from qsos;";
$res = $conn->query($sql);
$resa = $res->fetch_array();
$firstdate = $resa["qd"];

$sql = "select max(str_to_date(qsodate, \"%Y-%m-%d\")) as qd from qsos;";
$res = $conn->query($sql);
$resa = $res->fetch_array();
$lastdate = $resa["qd"];
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php echo $club_call; ?> QSL Print System</title>
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/qsl.css" rel="stylesheet">
	</head>

	<body>
		<header class="shadow-md bg-dark px-3">
			<div class="row">
				<h4><?php echo $club_call; ?> QSL Print System</h4>
			</div>
		</header>
	
		<main>	
			<div class="container">
				<div class="row">
					<div class="col-12 my-2">
						<center>
						<h3><?php echo $club_name; ?> - <?php echo $club_call; ?></h3>
						</center>
					</div>
				</div>
				<div class="row justify-content-around">
					<div class="col-5">
						<p>Welcome to the <?php echo $club_name; ?> QSL printing system.
						This system allows you retrieve and print QSLs for QSOs with the
						club call <?php echo $club_call; ?>. This system will provide
						you with any QSL on record in the <i>current</i> QSL card or certificate
						used by the club. To begin, enter your callsign below and click Search for QSOs.</p>
						<hr>
						<form action="qslfetch.php" method="post">
						<label for="call" class="form-label"><b>Enter the Callign to Search For:</b></label>
						<input type="text" name="call" class="form-control">
						<button type="submit" class="btn btn-primary my-2">Search for QSOs</button>
						</form>
					</div>
					<div class="col-5">
						<div class="alert alert-secondary">
							<div class="qsl-page-note">
								<b>First QSO Date:</b> <?php echo $firstdate; ?><br>
								<b>Last QSO Date:</b> <?php echo $lastdate; ?><hr>
								<?php print $qsl_page_note; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		<footer>
			<div class="d-flex">
				<p class="text-muted">Site information &copy;&nbsp;<?php print date("Y"); ?>&nbsp;<?php print $club_name; ?><br/>
				Powered by <a href="https://github.com/jxmx/smooth-qsl" target="_blank">Smooth QSL</a><br/>
				This page load
<?php
if(random_int(1,4) > 3){
	include("qslmaint.php");
	print("ran");
} else {
	print("did not run");
}
?>
			maintenance.</p>
			</div>
		</footer>
		<script src="js/bootstrap.min.js"></script>
		</body>
</html>
