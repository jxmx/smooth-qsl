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
<script src="js/index.js"></script>
EOT;

$qry = "SELECT MIN(STR_TO_DATE(qsodate, '%Y-%m-%d')) as qd from qsos;";
$qso_fdate = $db->singleValResult($qry);

$qry = "SELECT MAX(STR_TO_DATE(qsodate, '%Y-%m-%d')) as qd from qsos;";
$qso_ldate = $db->singleValResult($qry);

require_once(__DIR__ . "/header.php");
?>

<main>
<div class="container px-4">
	<div class="row mt-2 gx-5">
		<div class="col d-flex">
			<div class="p-3 shadow rounded-3">
				<p>Welcome to the <?php echo $club_name; ?> QSL printing system.
				This system allows you retrieve and print QSLs for QSOs with the
				club call <?php echo $club_call; ?>. This system will provide
				you with any QSL on record in the <i>current</i> QSL card or certificate
				used by the club. To begin, enter your callsign below and click Search for QSOs.</p>
				<hr>
				<form id="indexsearch" name="indexsearch" action="qslfetch.php"
					method="post" novalidate>
					<label for="call" class="form-label"><b>Enter the Callign to Search For:</b></label>
					<input type="text" name="call" class="form-control">
					<button type="submit" class="btn btn-primary my-2">Search for QSOs</button>
				</form>
			</div>
		</div>
		<div class="col d-flex">
			<div class="p-3 shadow rounded-3">
				<h5 class="rounded-3 ff-titlebars p-2 text-center">Callsign <?php echo $club_call; ?></h5>
				<p>
				<b>First QSO Date:</b> <?php echo $qso_fdate; ?><br>
				<b>Last QSO Date:</b> <?php echo $qso_ldate; ?>
				<hr>
				<?php print $qsl_page_note; ?>
				</p>
			</div>
		</div>
	</div>
</div>
</main>

<?php require_once("footer.php"); ?>