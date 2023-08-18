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
<?php include_once "qslconf.php"; ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php echo $club_call; ?> QSL Print System</title>
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/qsl.css" rel="stylesheet">
		<script src="js/smoothqsl.js"></script>
	</head>

	<body>
       <header class="shadow-md bg-dark px-3">
            <div class="row">
                <h4><?php echo $club_call; ?> QSL Print System</h4>
            </div>
        </header>

		<main>
		<div class="container">
<?php			
			
if( preg_match('/^[A-Za-z0-9\/]+$/', strcleaner($_POST["call"])) == 0){
	echo "<p class=\"lead\">Invalid callsign format provided, please used a standard
	amateur callsign such as W1AW, W1AW/P, W1AW/M, W1AW/8, VE3/W1AW, etc.</p>";
	goto end;	# C'mon, you know this is cleaner....
}

$call = strtoupper(strcleaner($_POST["call"]));

$conn = new mysqli($db_server, $db_user, $db_pass, $db_db);
if( $conn->connect_error){
	die("Connection failed: " . $conn->connect_error);
}	
$sql = sprintf("SELECT * FROM qsos WHERE callsign = '%s' ORDER BY qsodate,timeon DESC", $call);
$res = $conn->query($sql);

if( $res->num_rows < 1){
	echo "<p class=\"lead\">Sorry, no QSOs have been loaded into the database 
	for that callsign yet. Make sure you entered the call as you gave on the 
	air including any mobile, portable, country, or region prefix or suffix to the call.<p>\n";
	goto end;  # again, cleaner...
} 
?>
<p class="lead">Hello, <?php echo $call; ?>! Thank you for working
<?php echo $club_name . "&nbsp;(" . $club_call . ")"; ?>!</p>
<p>You have the following QSOs available for QSL download:</p>
<form action="qslprint.php" method="POST" name="qsofetch" id="qsofetch" onsubmit="return validateQsoFetchForm()">
<input type="hidden" name="call" value="<?php echo $call; ?>" />
<input type="hidden" name="maxqso" value="<?php echo $qsl_num_qso_rows; ?>" />
<table width="80%" class="table table-striped table-hover">
<thead>
	<tr>
		<th id="thle">Print&#8253;</th>
		<th>Callsign</th>
		<th>Timestamp</th>
		<th>Band/Freq</th>
		<th>RST</th>
<?php
if($qsl_qso_print_operator){
?>
		<th>Mode</th>
		<th id="thre">Operator / Station</th>
<?php
} else {
?>
		<th id="thre">Mode</th>
<?php
}
?>
	</tr>
</thead>
<?php
	while($row = $res->fetch_assoc()){
		print "<tr>\n";
		print "<td><input type=\"checkbox\" name=\"qq[]\" value=\"" . $row['qsoid'] . "\"></td>"; 
		print "<td>" . $row['callsign'] . "</td>";
		print "<td>" . $row['qsodate'] . " " . $row['timeon'] . "Z</td>\n";
		if(strlen($row['freq'] > 0)){
			print "<td>" . sprintf("%.03f", $row['freq']) . "</td>\n";
		} else {
			print "<td>" . $row['band'] . "</td>\n";
		}
		if(is_int(strlen($row['rstrcvd']))){
			print "<td>" . $row['rstrcvd'] . "</td>\n";
		} else {
			if(strcmp($row['mode'],"CW") or strcmp($row['mode'],"cw")){
				print "<td>599</td>\n";
			} else {
				print "<td>59</td>\n";
			}
		}
		print "<td>" . $row['mode'] . "</td>\n";
		if($qsl_qso_print_operator){
			print "<td>" . $row['operator'] . " on " . $row['station'] . "</td></tr>\n";
		}
	}
	print "</table><br>\n";
	$conn->close(); 
?>
<p>Click the checkbox next to each QSO you want to print on the certificate.
You may select up to <?php print $qsl_num_qso_rows; ?> QSOs per certificate.</p>
<button type="submit" class="btn btn-secondary">Retrieve</button>
</form>
<?php end: ?>
		</div>
		</main>
        <footer>
            <div class="d-flex">
                <p class="text-muted">Site information &copy;&nbsp;<?php print date("Y"); ?>&nbsp;<?php print $club_name; ?><br/>
                Powered by <a href="https://github.com/jxmx/smooth-qsl" target="_blank">Smooth QSL</a></p>
            </div>
        </footer>
		<script src="js/bootstrap.min.js"></script>
	</body>
</html>
