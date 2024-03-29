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

if( preg_match('/^[A-Za-z0-9]+$/', strcleaner($_POST['transid'])) == 0){
	echo "<html><head><title>ERROR</title></head><body><h1>Invalid transaction ID format</h1></body></html>";
	exit;
}
$transid = strtoupper(strcleaner($_POST['transid']));

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php echo $club_call; ?> QSL Print System ADIF Loader</title>
		<link href="../css/bootstrap.min.css" rel="stylesheet">
		<link href="../css/qsl.css" rel="stylesheet">

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
$conn = new mysqli($db_server, $db_user, $db_pass, $db_db);
if( $conn->connect_error){
	die("Connection failed: " . $conn->connect_error);
}	
$sql = sprintf("SELECT transdata FROM trans WHERE transid = '%s'", $transid);
$res = $conn->query($sql);

if(!$res){
	printf("<p class=\"lead\">There was an unexpected SQL serror with
		that transaction. Transaction ID:<br>%s<p>\n", $transid);
	goto end;  # again, cleaner...	
}

if( $res->num_rows < 1){
	printf("<p class=\"lead\">There appears to be no pending commit
		for that transaction ID. Transaction ID missing is:<br>%s<p>\n", $transid);
	print("<p class=\"lead\">Transactions must be committed within 5 minutes of staging
	or they may be deleted by the system</p>");
	goto end;  # again, cleaner...
}

if( $res->num_rows > 1){
	printf("<p class=\"lead\">There appears to be multiple pending identical transactions
		and that isn't good. Transaction ID is:<br>%s<p>\n", $transid);
	print("<p class=\"lead\">If you are receiving this after re-uploading an ADIF
	that you did not commit to make changes you must wait 5 minutes between submissions
	for the previous transaction to timeout.");
	goto end;  # again, cleaner...
}

# insert the transition log
list($transdata) = $res->fetch_row();
$sql = sprintf("INSERT INTO loadlog(loadcall,tstamp,transid) VALUES (\"%s\",\"%s\",\"%s\")",
	$csign, date("Y-m-d H:i:s"), $transid);

if( $conn->query($sql) === false ){
	printf("<p class=\"lead\">Could not write the loadlog to the DB. Transaction 
		ID is:<br>%s</p>\n", $transid);
	goto end;
}

# get the id of the last log
$sql = sprintf("SELECT LAST_INSERT_ID()");

# Insertion transaction
$res = $conn->query($sql);
list($logid) = $res->fetch_row();

$conn->begin_transaction();
$vals = base64_decode($transdata);
$vals = preg_replace("/\)/",",$logid)",$vals);
$inserts = explode("),(", $vals);
foreach( $inserts as $insert){
	$insert = preg_replace("/[\(\)]/", "",  $insert);
	$sql = sprintf("INSERT INTO qsos (callsign,band,freq,rstrcvd,qsodate," .
			"timeon,operator,station,mode,county,logid) VALUES (%s)",
			$insert);
	$res = $conn->query($sql);
	if( $res === false ){
		printf("<p class=\"lead\">Individual transaction insert failed due to bad input</p>");
		printf("<pre>%s</pre>", $conn->error);
		printf("<pre>%s</pre>", $sql);
		printf("Transaction ID is:<br>%s</p>\n", $transid);
		$conn->rollback();
	    goto end;
	}
} 

if( $conn->commit() === false ){
	$conn->rollback();
	printf("<p class=\"lead\">Could not write the QSO transaction to the DB. Transaction 
		ID is:<br>%s</p>\n", $transid);
	goto end;
}

$sql = sprintf("DELETE FROM trans WHERE transid=\"%s\"", $transid);
if( $conn->query($sql) === false ){
	printf("<p class=\"lead\">Could not unlock transaction. Transaction 
		ID is:<br>%s</p>\n", $transid);
	goto end;
}

printf("<p class=\"lead\">Thanks for your submission, %s. These QSOs have been saved. You may either close this window or <a href=\"index.php\">load another ADIF</a>.", $csign);
printf("<p>Transaction ID: %s</p>", $transid);
end:
$conn->close();
?>
		</div>
		</main>
        <footer>
            <div class="d-flex">
                <p class="text-muted">Site information &copy;&nbsp;<?php print date("Y"); ?>&nbsp;<?php print $club_name; ?><br/>
                Powered by <a href="https://github.com/jxmx/smooth-qsl" target="_blank">Smooth QSL</a></p>
            </div>
        </footer>

		<script src="../js/bootstrap.min.js"></script>
	</body>
</html>
<?php include("../qslmaint.php");?>
