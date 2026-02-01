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

$ff_header_content = "<h2>ADIF Loader - Step 2</h2>";

include_once "adif_parser.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	http_error_response(405, "invalid request method");
}

if( !is_callsign($_POST['csign']) ){
	http_error_response(400, "invalid callsign format");
}

if(!isset($_POST["location"])){
	http_error_response(400, "missing element location");
}


if( empty($_FILES['adiffile'] )){
	http_error_response(401, "invalid ADIF payload");
}

$csign = strtoupper(strcleaner($_POST['csign']));
$location = strcleaner($_POST["location"]);

// Sanitize file name to prevent directory traversal attacks
$logs = adif_to_array($_FILES['adiffile']['tmp_name'], $csign, $location);
$_SESSION['logs'] = $logs;
include_once("header.php");
?>
<main>
	<div class="container">
		<div class="row">
			<div class="col-12 shadow rounded-3">
			The following QSOs were found in the ADIF
			file provided. Please review the QSOs and then click <b>Commit</b> at
			the bottom of the table. If there is a problem with any entry below,
			please click your browser's back button, fix your ADIF file, and
			re-upload.<br>
			<b><i>Please note that this tool does not deduplicate QSOs
			so please make sure not to upload any duplicates.</i></b></p>
			<table class="table table-sm table-striped table-hover">
				<thead class="rounded-3">
					<tr>
						<th>Date</th>
						<th>Time</th>
						<th>Call</th>
						<th>Freq</th>
						<th>Band</th>
						<th>Mode</th>
						<th>RST</th>
						<th>Operator</th>
						<th>Comment</td>
					</tr>
				</thead>
			<tbody>
<?php

foreach($logs as $log){
	$trunc_comment = substr($log["comment"], 0, 20);
	print <<<EOT
		<tr>
			<td>{$log["date"]}</td>
			<td>{$log["time"]}</td>
			<td>{$log["call"]}</td>
			<td>{$log["freq"]}</td>
			<td>{$log["band"]}</td>
			<td>{$log["mode"]}</td>
			<td>{$log["rst"]}</td>
			<td>{$log["operator"]}</td>
			<td>{$trunc_comment}</td>
		</tr>
	EOT;
}
?>
				</tbody>
			</table>
			<a class="btn btn-primary mt-2 mb-3" href="load_adif_commit.php">Commit Log Records</a>
		</div>
	</div>
</main>
<?php require_once("footer.php"); ?>
