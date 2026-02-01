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

include_once(__DIR__ . "/lib/include.php");
require_login();

// This is the page title in <head>>. It's followed by "| Firefly QSL"
$ff_page_title = $club_call;
$ff_header_content = "<h2>ADIF Loader - Step 3</h2>";

if(!isset($_SESSION['logs'])){
	http_error_response(400, "cannot be called outside of existing transaction");
}

include_once("header.php");
?>
<main>
	<div class="container">
		<div class="row mt-3">
			<div class="col-12 shadow rounded-3 text-center p-3">
<?php
try{
	$logs = $_SESSION['logs'];

	$db->pdo()->beginTransaction();
	$batchSize = 500;
	$buffer = [];

	$sql = <<<EOT
		INSERT INTO qsos (callsign, band, freq, rstrcvd, qsodate,
		timeon, operator, station, mode, location, comment) VALUES
		EOT;

	$placeholders = "(?,?,?,?,?,?,?,?,?,?,?)";

	$stmt = $db->pdo()->prepare($sql .
		implode(',', array_fill(0, $batchSize, $placeholders)));

	foreach ($logs as $i => $log) {
		$buffer = array_merge($buffer, [
			$log['call'],
			$log['band'],
			$log['freq'],
			$log['rst'],
			$log['date'],
			$log['time'],
			$log['operator'],
			$log['station'],
			$log['mode'],
			$log['location'],
			$log['comment']
		]);

		if (($i + 1) % $batchSize === 0) {
			$stmt->execute($buffer);
			$buffer = [];
		}
	}

	if (!empty($buffer)) {
		$stmt = $db->pdo()->prepare(
			$sql . implode(',', array_fill(0, count($buffer) / 11, $placeholders))
		);
		$stmt->execute($buffer);
	}

	$db->pdo()->commit();

	unset($_SESSION['logs']);

	print <<<EOT
		<div class="alert alert-success text-center mx-5 mb-5"><h2>Logs Loaded</h2></div>
		EOT;

	$qry = "SELECT COUNT(qsoid) FROM qsos";
	$qso_count = $db->singleValResult($qry);

	$qry = "SELECT MIN(STR_TO_DATE(qsodate, '%Y-%m-%d')) as qd from qsos;";
	$qso_fdate = $db->singleValResult($qry);

	$qry = "SELECT MAX(STR_TO_DATE(qsodate, '%Y-%m-%d')) as qd from qsos;";
	$qso_ldate = $db->singleValResult($qry);

	print <<<EOT
		<div class="alert alert-info text-center mx-5 my-5">
		<b>Current QSO Count:</b> {$qso_count}<br>
		<b>First QSO:</b> {$qso_fdate}<br>
		<b>Last QSO:</b> {$qso_ldate}
		</div>

		<a class="btn btn-success" href="index.php">Home</a>

	EOT;

} catch(Exception $e){
	if( $db->pdo()->inTransaction() ){
        $db->pdo()->rollBack();
    }

	print <<<EOT
		<div class="alert alert-danger text-center mx-5 my-5">
			<h2>Logs Insert Error</h2>
		EOT;
	print($e->getMessage());
	print <<<EOT
		</div>
		EOT;
}
?>
			</div>
		</div>
	</div>
</main>
<?php require_once("footer.php"); ?>
