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
<?php include_once "../qslconf.php"; ?>
<?php include_once "adif_parser.php"; ?>
<?php
if( !isset($_POST["submit"])){
	echo "<html><head><title>ERROR</title></head><body><h1>Invalid Call Type Error</h1></body></html>";
	exit;
}

if( preg_match('/^[A-Za-z0-9]+$/', strcleaner($_POST['csign'])) == 0){
	echo "<html><head><title>ERROR</title></head><body><h1>Invalid callsign format provided, 
	please used a standard amateur callsign such as W1AW and no / modifiers.</h1></body></html>";
	exit;
}
$csign = strtoupper(strcleaner($_POST['csign']));

if(!isset($_POST["loadkey"])){
	echo "<html><head><title>ERROR</title></head><body><h1>Invalid Parameters Error</h1></body></html>";
	exit;	
}

$load_key = strcleaner($_POST["loadkey"]);
if( strcmp($qsl_load_key, $load_key) !== 0 ){
	echo "<html><head><title>ERROR</title></head><body><h1>Invalid Load Key</h1></body></html>";
	exit;
}

if( !is_uploaded_file($_FILES['adiffile']['tmp_name'])){
	echo "<html><head><title>ERROR</title></head><body><h1>Nice try...</h1></body></html>";
	exit;	
}

if(!isset($_POST["county"])){
	echo "<html><head><title>ERROR</title></head><body><h1>Invalid Parameters Error</h1></body></html>";
	exit;	
}


?>
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
			<div class="qsl">
			<p>Hello <?php echo $csign; ?>. The following QSOs were found in the ADIF
			file provided. Please review the QSOs and then click <b>Commit</b> at
			the bottom of the table. If there is a problem with any entry below,
			please click your browser's back button, fix your ADIF file, and
			re-upload.<br><i>Please note that this tool does not deduplicate QSOs
			so please make sure not to upload any duplicates.</i></p>
			<table class="table table-striped table-hover">
			<thead>
				<tr>
					<th id="thle">Date</th>
					<th>Time</th>
					<th>Call</th>
					<th>Freq</th>
					<th>Band</th>
					<th>Mode</th>
					<th>RST</th>
					<th id="thre">Operator</th>
				</tr>
			</thead>
			<tbody>
<?php

$adif = new ADIF_Parser;
$adif->load_from_file($_FILES['adiffile']['tmp_name']);
$adif->initialize();
$insert = "";
while($rec = $adif->get_record()){
	if(count($rec) == 0){
		break;
	}

	printf("<tr>");
	$a_date = preg_replace('/^([0-9]{4})([0-9]{2})([0-9]{2})$/', '$1-$2-$3', 
		numcleaner($rec["qso_date"]));
	printf("<td>%s</td>", $a_date);
	$a_time = preg_replace('/^([0-9]{2})([0-9]{2})([0-9]{2})$/', '$1:$2', 
		numcleaner($rec["time_on"]));
	printf("<td>%s</td>", $a_time);
	$a_call = strtoupper(strcleaner($rec["call"]));
	printf("<td>%s</td>", $a_call);
	$a_freq = strcleaner($rec["freq"]);
	printf("<td>%s</td>", $a_freq);
	$a_band = "";
	if(isset($rec["band"])){
		$a_band = strcleaner($rec["band"]);
	} else {
		$a_band = "";
	}
	printf("<td>%s</td>", $a_band);
	$a_mode = strcleaner($rec["mode"]);
	printf("<td>%s</td>", $a_mode);
	$a_rst = strcleaner($rec["rst_rcvd"]);
	printf("<td>%s</td>", $a_rst);
	$a_oper = "";
	if(!isset($rec["operator"]) || strlen($rec["operator"]) == 0){
		$a_oper = $csign;
	} else {
		$a_oper = $rec["operator"];
	}
	printf("<td>%s</td>", $a_oper);	
	printf("</tr>");
	
	#callsign,band,freq,rstrcvd,qsodate,timeon,operator,station,mode,county
	$insert .= sprintf("(\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\"),", 
			addslashes($a_call), addslashes($a_band), addslashes($a_freq), addslashes($a_rst), 
			$a_date, $a_time, addslashes($a_oper), $club_call, addslashes($a_mode),
			addslashes(strcleaner($_POST["county"])));

}

?>
				</tbody>
				</table>
<?php

# trim off the last comma and encode for storage
$insert = base64_encode(substr($insert, 0, strlen($insert) - 1));
$transid = hash("sha256", $insert);

# insert the transaction
$conn = new mysqli($db_server, $db_user, $db_pass, $db_db);
if( $conn->connect_error){
	die("Connection failed: " . $conn->connect_error);
}	
$sql = sprintf("INSERT INTO trans (transid,transdata,transtimet) VALUES ('%s','%s','%s')",
	$transid, $insert, time());

if( $conn->query($sql) === true ){
	$sql = sprintf("DELETE FROM trans WHERE transtimet < %d", time() - 300);
	$conn->query($sql);
?>

				<form method="post" action="qsladifloadcommit.php">
				<input type="hidden" name="transid" value="<?php echo $transid; ?>">
				<input type="hidden" name="csign" value="<?php echo $csign; ?>">
				<input type="hidden" name="loadkey" value="<?php echo $load_key; ?>">
				<input id="submit" type="submit" name="submit" value="Commit QSOs">
				</form>
<?php
} else {
	echo "<p class=\"lead\">ERROR: There was a problem with the database staging
	the transaction for commit. Please contact the system administrator.</p>";
}
?>
			</div>
		</div>
		<div id="footer">
            <div class="container">
                <hr>
                <p class="text-muted">Site information &copy;&nbsp;<?php print date("Y"); ?>&nbsp;<?php print $club_name; ?><br/>
                Powered by <a href="https://github.com/jxmx/smooth-qsl" target="_blank">Smooth QSL</a></p>
            </div>
        </div>
		<script src="../js/bootstrap.min.js"></script>
	</body>
</html>
