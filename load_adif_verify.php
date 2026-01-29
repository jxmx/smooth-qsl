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
include_once(__DIR__ . "/lib/include.php");

// This is the page title in <head>>. It's followed by "| Firefly QSL"
$ff_page_title = $club_call;

$ff_header_content = "<h2>ADIF Loader - Step 2</h2>";

include_once "adif_parser.php";

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

include_once("header.php");
?>
<main>
	<div class="container">
		<div class="row">
			<div class="col-12">
			<p>Hello <?php echo $csign; ?>. The following QSOs were found in the ADIF
			file provided. Please review the QSOs and then click <b>Commit</b> at
			the bottom of the table. If there is a problem with any entry below,
			please click your browser's back button, fix your ADIF file, and
			re-upload.<br>
			<b><i>Please note that this tool does not deduplicate QSOs
			so please make sure not to upload any duplicates.</i></b></p>
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

	$a_date = preg_replace('/^([0-9]{4})([0-9]{2})([0-9]{2})$/', '$1-$2-$3',
		clean_int($rec["qso_date"]));

	$a_time = preg_replace('/^([0-9]{2})([0-9]{2})([0-9]{2})$/', '$1:$2',
		strcleaner($rec["time_on"]));

	$a_call = strtoupper(strcleaner($rec["call"]));

	$a_freq = clean_float($rec["freq"]);

	$a_band = "";
	if(isset($rec["band"])){
		$a_band = strcleaner($rec["band"]);
	} else {
		$a_band = "";
	}

	$a_mode = strcleaner($rec["mode"]);

	$a_rst = strcleaner($rec["rst_rcvd"]);

	$a_oper = "";
	if(!isset($rec["operator"]) || strlen($rec["operator"]) == 0){
		$a_oper = $csign;
	} else {
		$a_oper = $rec["operator"];
	}


	print <<<EOT
		<tr>
			<td>{$a_date}</td>
			<td>{$a_time}</td>
			<td>{$a_call}</td>
			<td>{$a_freq}</td>
			<td>{$a_band}</td>
			<td>{$a_mode}</td>
			<td>{$a_rst}</td>
			<td>{$a_oper}</td>
		</tr>
		EOT;

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
</main>
<?php require_once("footer.php"); ?>
