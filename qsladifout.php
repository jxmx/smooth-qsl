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
<?php

$conn = new mysqli($db_server, $db_user, $db_pass, $db_db);
if( $conn->connect_error){
	die("Connection failed: " . $conn->connect_error);
}	

printf("<ADIF_VERS:%d>%s <PROGRAMID:%d>%s <PROGRAMVERSION:%d>%s> <eoh>",
	strlen("2.2.7"), "2.2.7", strlen("SmoothQSL ADIF Export"), "SmoothQSL ADIF Export",
	strlen("1.0"), "1.0");
	
$sql = "SELECT * FROM qsos";
$res = $conn->query($sql);

while($row = $res->fetch_assoc()){
	$r = sprintf("%s,%s,%s,%s,%s,%s,%s,%s,%s",
		$row["callsign"], $row["band"], $row["freq"], $row["rstrcvd"],
		$row["qsodate"], $row["timeon"], $row["operator"], $row["station"], $row["mode"], $row['county']);

	printf("<CALL:%d>%s ", strlen($row["callsign"]), $row["callsign"]);
	printf("<FREQ:%d>%s ", strlen($row["freq"]), $row["freq"]);
	printf("<MODE:%d>%s ", strlen($row["mode"]), $row["mode"]);
	printf("<MY_CNTY:%d>%s ", strlen($row["county"]), $row["county"]);
	printf("<OPERATOR:%d>%s ", strlen($row["operator"]), $row["operator"]);
	printf("<STATION_CALLSIGN:%d>%s ", strlen($row["station"]), $row["station"]);
	$qdate = str_replace("-", "", $row["qsodate"]);
	printf("<QSO_DATE:%d>%s ", strlen($qdate), $qdate);
	$qtime = str_replace(":", "", $row["timeon"]);
	printf("<TIME_ON:%d>%s ", strlen($qtime), $qtime);
	printf("<PROGRAMID:%d>%s ", strlen("SmoothQSL ADIF Export"), "SmoothQSL ADIF Export");
	printf("<PROGRAMVERSION:%d>%s ", strlen("1.0"), "1.0");
	print "<EOR>\n\n";
}

$conn->close();
?>
