<?php
/*
Copyright 2017-2025 Jason D. McCormick

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

$ff_additional_scripts = <<<EOT
<script src="js/jquery-4.0.0.min.js"></script>
<script src="js/jquery.validate-1.22.0.min.js"></script>
<script src="js/load.js"></script>

EOT;

// This is the page title in <head>>. It's followed by "| Firefly QSL"
$ff_page_title = $club_call;

$ff_header_content = "<h2>ADIF Loader - Step 1</h2>";

include_once("header.php");
?>
<main>
<div class="container px-4">
	<div class="row mt-2 gx-5">
		<div class="col-8 d-flex">
			<div class="p-3 shadow rounded-3 flex-fill">
				<p>To use the loader enter your call sign, the load key provided by
				your club QSL manager in the <i>Load Key</i> box, select your ADIF file for
				uploading, and then click the upload button. The loader supports ADIF 2.0
				and the ADIF v3.0 non-XML formats. <i>This system will not deduplicate logs</i></p>

				<form name="loadform" id="loadform" method="post" action="qsladifloader.php" novalidate>

				<label for="csign" class="form-label mt-2">Callsign</label>
				<input id="csign" name="csign" class="form-control" aria-describedby="csign-help" type="text">
				<div id="csign-help" class="form-text">Note: Do not use any trailing /M, /P, etc.</div>

				<label for="loadkey" class="form-label mt-2">Loadkey</label>
				<input id="loadkey" name="loadkey" class="form-control" aria-describedby="loedkey-help" type="password">
				<div id="loadkey-help" class="form-text">This is the load key/password set in the configuration that
					authorizes the uploading of logs.
				</div>

				<label for="adiffile" class="form-label mt-2">ADIF File</label>
				<input id="adiffile" name="adiffile" class="form-control" aria-describedby="adiffile-help" type="file">
				<div id="adiffile-help" class="form-text"><span style="color:red">Note: Max file size supported by server is
					<?php print(ini_get("upload_max_filesize")) ?></span></div>

				<label for="county" class="form-label mt-2">QTH / Location</label>
				<input id="county" name="county" class="form-control" aria-describedby="county-help" type="text">
				<div id="county-help" class="form-text">Enter your operating location for these QSOs.</div>

				<button type="submit" class="btn btn-primary mt-2" id="submit" name="submit">Upload ADIF</button>

				</form>
			</div>
		</div>

		<div class="col-4">
			<div class="p-3 shadow rounded-3">
				<div class="alert alert-warning">
					<p><strong>Note about ADIF Exports:</strong> This system may not work
					with ADIF files from logging software that allows you to choose
					which fields to export if you do not choose all of the
					needed fields. When exporting ADIF files from your
					logging software of choice, you must select <i>a least</i>
					the following ADIF fields for the export:
					<ul>
					<li>QSO Date</li>
					<li>Time On</li>
					<li>Call</li>
					<li>Freq (or Band)</li>
					<li>Mode</li>
					<li>RST RCVD</li>
					<li>Operator</li>
					</ul>
					</p>
				</div>
			</div>
		</div>
	</div>
</main>
<?php require_once("footer.php"); ?>