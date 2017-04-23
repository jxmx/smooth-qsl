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
<?php include_once "qslconf.php"; ?>
<?php

$conn = new mysqli($db_server, $db_user, $db_pass, $db_db);
if( $conn->connect_error){
	die("Connection failed: " . $conn->connect_error);
}	
$sql = "SELECT * FROM qsos";
$res = $conn->query($sql);

if( $res->num_rows < 1){
	if($db_maint_log){ print "No QSOs; stopping\n"; }
	goto end;  # again, cleaner...
} 

while($row = $res->fetch_assoc()){
	$r = sprintf("%s,%s,%s,%s,%s,%s,%s,%s,%s",
		$row["callsign"], $row["band"], $row["freq"], $row["rstrcvd"],
		$row["qsodate"], $row["timeon"], $row["operator"], $row["station"], $row["mode"]);
	$rh = hash("sha256", $r);
	$qhashes[$rh][$row["qsoid"]] = $row["tstamp"];
}

# run through each sub array, sort by the timestamp, pop off the last one because
# it's the newest, and then pack the rest into the $discard array;
$discard = [];
foreach($qhashes as $qhash){
	if(count($qhash) > 1){
		asort($qhash, SORT_STRING);
		$keep = array_pop($qhash);
		foreach($qhash as $k => $v){
			array_push($discard, $k);
		}
	}
}

# build the $discard into a list for a drop
$discardl = "(";
foreach($discard as $k => $v){
	$discardl .= sprintf("%s,", $v);
}
$discardl = substr($discardl,0,strlen($discardl)-1);
$discardl .= ")";

if($discardl > 1){
	$sql = sprintf("DELETE FROM qsos WHERE qsoid IN %s", $discardl);
	if( $conn->query($sql) === false ){
		printf("Failed to execute:\n\n%s\n", $sql);
		goto end;
	}
}

$sql = "OPTIMIZE TABLE trans";
if( $conn->query($sql) === false ){
	printf("Failed to execute:\n\n%s\n", $sql);
	goto end;
}

end:
$conn->close();
?>
