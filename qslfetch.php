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

// This is the page title in <head>>. It's followed by "| Firefly QSL"
require_once(__DIR__ . "/lib/include.php");

$ff_page_title = $club_call;
$ff_header_content = sprintf("<h2>%s QSLs</h2>", $club_name);

$ff_additional_scripts = <<<EOT
<script src="js/jquery-4.0.0.min.js"></script>
<script src="js/jquery.validate-1.22.0.min.js"></script>
<script src="js/qslfetch.js"></script>
EOT;

$qry = "SELECT MIN(STR_TO_DATE(qsodate, '%Y-%m-%d')) as qd from qsos;";
$qso_fdate = $db->singleValResult($qry);

$qry = "SELECT MAX(STR_TO_DATE(qsodate, '%Y-%m-%d')) as qd from qsos;";
$qso_ldate = $db->singleValResult($qry);

require_once(__DIR__ . "/header.php");

if( ! is_callsign($_POST["call"]) ){
	http_error_response(400, "invalid callsign format");
}

$call = strtoupper(strcleaner($_POST["call"]));
?>

<main>
	<div class="container">
		<div class="row mt-3">
			<div class="col-12 shadow rounded-3 text-center p-3">

<?php

try{
	$qry = "SELECT * FROM qsos WHERE callsign = ? ORDER BY qsodate,timeon DESC";
	$qry_params = [ $call ];
	$stmt = $db->pdo()->prepare($qry);
	$stmt->execute($qry_params);
	$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

	if( count($rows) > 0 ){
		if($qsl_qso_print_operator){
			$th_operator = "<th>Operator / Station</th>";
		} else {
			$th_operator = "";
		}

		print <<<EOT
			<p class="lead">Hello, {$call}! Thank you for working
			{$club_name} ({$club_call})!</p>
			<p>You have the following QSOs available for QSL download:</p>

			<form action="qslrender.php" method="POST"
				name="qsofetch" id="qsofetch" onsubmit="return validateQsoFetchForm()">
			<input type="hidden" name="call" value="{$call}" />
			<input type="hidden" name="maxqso" value="{$qsl_num_qso_rows}" />

			<table width="80%" class="table table-striped table-hover">
			<thead>
				<tr>
					<th>Print&#8253;</th>
					<th>Callsign</th>
					<th>Timestamp</th>
					<th>Band/Freq</th>
					<th>RST</th>
					<th>Mode</th>
					{$th_operator}

				</tr>
			</head>
			EOT;

		foreach($rows as $row){
			$qsoid = $row['qsoid'];
			$callsign = $row['callsign'];
			$qsodate = sprintf("%s %s", $row['qsodate'], $row['timeon']);

			if(strlen($row['freq'])){
				$freqmode = sprintf("%.04f", $row['freq']);
			} else {
				$freqmode = $row['band'];
			}

			if(is_int(strlen($row['rstrcvd']))){
				$rstrcvd =  $row['rstrcvd'];
			} else {
				$rstrvcd = "--";
				if(strcmp($row['mode'],"CW") or strcmp($row['mode'],"cw")){
					$rstrcvd = "599";
				} else {
					$rstrcvd = "59";
				}
			}

			$mode = $row['mode'];

			if($qsl_qso_print_operator){
				$operator = sprintf("<td>%s on %s</td>", $row['operator'], $row['station']);
			} else {
				$operator = "";
			}

			print <<<EOT
				<tr>
					<td><input type="checkbox" name="qq[]" value="{$qsoid}"></td>
					<td>{$callsign}</td>
					<td>{$qsodate}</td>
					<td>{$freqmode}</td>
					<td>{$rstrcvd}</td>
					<td>{$mode}</td>
					{$operator}
				</tr>
			EOT;
		}

		print <<<EOT
			</table>

			<p>Click the checkbox next to each QSO you want to print on the certificate.
			You may select up to {$qsl_num_qso_rows} QSOs per certificate.</p>

			<button type="submit" class="btn btn-secondary">Retrieve</button>
			</form>
		EOT;

	} else {
		print <<<EOT
		<div class="alert alert-warning">
		Sorry, no QSOs have been loaded into the database for
		that callsign (yet). Make sure you entered the call
		as you gave on the air including any mobile, portable,
		country, or region prefix or suffix to the call.</div>
		EOT;
	}

} catch(Exception $e){
	$error = $e->getMessage();
	print <<<EOT
		<div class="alert alert-danger text-center">
		<h5>Database Error</h2>
		<p>Sorry, there appears to have been a database error and this cannot continue.</p>
		</div>
	EOT;
}
?>

	</div>
</main>
<?php require_once("footer.php"); ?>