<?php
/*
Copyright 2017-2026 Jason D. McCormick

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

$options = getopt("c:f:l:");

if( preg_match('/^[A-Za-z0-9]+$/', strcleaner($options['c'])) == 0){
	print "ERROR: Invalid callsign format or missing -c CALLSIGN option\n";
	exit;
}
$csign = strtoupper(strcleaner($options['c']));

if(!isset($options["l"])){
	print "ERROR: Missing required -l LOCALE option";
	exit;	
}


printf("%s QSL Print System\n\n", $club_call);

print("The following QSOs were found in the ADIF file provided\n");

printf("Time\tCall\tFreq\tBand\tMode\RST\tOp\n");

$adif = new ADIF_Parser;
$adif->load_from_file($options['f']);
$adif->initialize();
$insert = "";
while($rec = $adif->get_record()){
	$starttime = microtime(true);
	if(count($rec) == 0){
		break;
	}

	$a_date = preg_replace('/^([0-9]{4})([0-9]{2})([0-9]{2})$/', '$1-$2-$3', 
		numcleaner($rec["qso_date"]));
	$a_time = preg_replace('/^([0-9]{2})([0-9]{2})([0-9]{2})$/', '$1:$2', 
		numcleaner($rec["time_on"]));
	$a_call = strtoupper(strcleaner($rec["call"]));
	$a_freq = strcleaner($rec["freq"]);
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
	printf("%s %s\t%s\t%s\t%s\t%s\t%s\t%s\n",
		$a_date, $a_time, $a_call, $a_freq, $a_band, $a_mode, $a_rst, $a_oper);
	
	#callsign,band,freq,rstrcvd,qsodate,timeon,operator,station,mode,county
	$insert .= sprintf("(\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\"),", 
			addslashes($a_call), addslashes($a_band), addslashes($a_freq), addslashes($a_rst), 
			$a_date, $a_time, addslashes($a_oper), $club_call, addslashes($a_mode),
			addslashes(strcleaner($options["l"])));

	$endtime = microtime(true);
	printf("Record processed in %0.6f s\n", $endtime - $starttime);
}

$starttime = microtime(true);
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
} else {
	print("ERROR: Problem staging database commit transaction\n");
	exit(1);
}
$endtime = microtime(true);
printf("Database insert prepped in %0.6f s\n", $endtime - $starttime);

exit(0);
