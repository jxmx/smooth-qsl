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
			<div class="col-12">
				<center>
				<h4><?php echo $club_call; ?> ADIF Loader</h4>
				</center>
			</div>
			<div class="row">
				<div class="col-md-8">
						<p>To use the loader enter your call sign, the load key provided by 
						your club QSL manager in the <i>Load Key</i> box, select your ADIF file for 
						uploading, and then click the upload button. The loader supports ADIF 2.0
						and the ADIF v3.0 non-XML formats. <b><i>This system will not deduplicate logs</i></b></p>

						<form method="post" action="qsladifloader.php" enctype="multipart/form-data">

							<p>
							<label>Callsign:</label>
							<input type="text" size="30" name="csign" /><br>
							<i>Note: Do not use any trailing /M, /P, etc.</i>
							</p>

							<p>
							<label>Load Key:</label>
							<input type="text" size="30" name="loadkey" />	
							</p>

							<p>
							<label>ADIF File:</label>
							<input type="file" name="adiffile" id="adiffile" />
							</p>
							<p>
							<span style="color:red">Note: Max file size supported by server is
							<?php print(ini_get("upload_max_filesize")) ?></span>
							</p>
	

							<p>
							<label>QTH:</label>
							<input type="text" size="30" name="county" /><br/>
							<i>Enter your operating location for these QSOs</i>
							</p>

							<p>
								<button type="submit" class="btn btn-secondary" id="submit" name="submit">Upload ADIF</button>
							</p>
						</form>
				</div>
				<div class="col-md-4">
					<div class="qsl">
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
		</div>
		<div id="footer">
            <div class="container">
                <hr>
                <p class="text-muted">Site information &copy;&nbsp;<?php print date("Y"); ?>&nbsp;<?php print $club_name; ?><br/>
                Powered by <a href="https://github.com/jxmx/smooth-qsl" target="_blank">Smooth QSL</a></p>
            </div>
        </div>
		<script src="../js/bootstrap.min.js"></script>
	</body>
</html>
