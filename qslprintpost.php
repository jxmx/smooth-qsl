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

$conn->close();

# Emit!
$imgfile = sprintf("cards/%s.jpg", uniqid("$club_call-", true));
$image->writeImages($imgfile, true);
//printf("<html><head><meta http-equiv=\"refresh\" content=\"0; url=%s\" /></head></html>", $imgfile);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $club_call; ?> QSL Print System</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/qsl.css" rel="stylesheet">
    </head>

    <body>
        <header class="shadow-md bg-dark px-3">
            <div class="row">
                <h4><?php echo $club_call; ?> QSL Print System</h4>
            </div>
        </header>

        <main>
            <div class="container">
				<div class="row p-3">
					<center>
						<a class="btn btn-secondary" role="button" href="<?php print $imgfile; ?>" download>Click Here to Download Image for Printing</a>
					</center>
				</div>
                <div class="row">
                    <div class="col-12">
						<center>
							<img class="img-fluid qsl-img" src="<?php print $imgfile; ?>" alt="QSL Card Image">
						</center>
                    </div>
                </div>
			</div>
		</main>
        <footer>
            <div class="d-flex">
                <p class="text-muted">Site information &copy;&nbsp;<?php print date("Y"); ?>&nbsp;<?php print $club_name; ?><br/>
                Powered by <a href="https://github.com/jxmx/smooth-qsl" target="_blank">Smooth QSL</a></p>
            </div>
        </footer>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/smoothqsl.js"></script>
	</body>
</html>

