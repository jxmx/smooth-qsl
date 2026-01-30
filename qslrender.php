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

# Sanitize the call variable against injection
if( ! is_callsign($_POST["call"]) ){
	http_error_response(400, "invalid callsign format");
}
$call = strtoupper(strcleaner($_POST["call"]));

# Build the "IN" array for the SELECT statement, checking the items for safety

if( ! isset($_POST['qq'])){
	http_error_response(400, "invalid parameter - missing POST qq");
}
$qsos = $_POST['qq'];

// Ensure it's an array
if (!is_array($qsos)) {
    $qsos= [$qsos];
}

$q_ids = [];

// Validate each element as an integer
foreach ($qsos as $item) {
    $val = filter_var($item, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
    if ($val !== null) {
        $q_ids[] = $val;
    }
}

// If nothing valid, handle gracefully
if (empty($q_ids)) {
    http_error_response(400, "invalid parameter - empty q_ids");
}

// Build placeholders for prepared statement
$q_ids_list = implode(',', array_fill(0, count($q_ids), '?'));
$qry = "SELECT * FROM qsos WHERE qsoid IN ($q_ids_list) ORDER BY qsodate,timeon ASC";
$qry_params = $q_ids;

$stmt = $db->pdo()->prepare($qry);
$stmt->execute($qry_params);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

if( count($rows) == 0){
	http_error_response(400, "mull results from bad POST");
}

// Vary the draw context based on single or multi-qsos
$qsl_context = 1;
$qsl_context_template = $qsl_template;
$qsl_context_gravity = $qsl_callsign_center_gravity;
if( count($rows) > 1){
    $qsl_context = 2;
    $qsl_context_template = $qsl_template_multi;
    $qsl_context_gravity = $qsl_callsign_center_gravity_multi;
}

// All the common rendering options

# Initialize the ImageMagick item
$image = new Imagick($qsl_context_template);
$image->setImageFormat('pdf');

# Draw the Callsign
$draw = new ImagickDraw();
$color = new ImagickPixel($qsl_c_font_color);
$draw->setFont($qsl_c_font);
$draw->setFontSize($qsl_c_font_size);
$draw->setFillColor($color);
$draw->setStrokeAntialias($qsl_c_font_aa);
$draw->setTextAntialias($qsl_c_font_aa);
if($qsl_context_gravity){
	$image->setGravity(imagick::GRAVITY_CENTER);
}
$draw->annotation($qsl_callsign_horiz_offset_multi, $qsl_callsign_vert_offset_multi, $call);
$image->drawImage($draw);

if($qsl_context_gravity){
	$image->setGravity(imagick::GRAVITY_NORTHWEST);
}

# Draw the QSO(s)
$draw = new ImagickDraw();
$color = new ImagickPixel($qsl_font_color);
$draw->setFont($qsl_font);
$draw->setFontSize($qsl_font_size);
$draw->setFillColor($color);
$draw->setStrokeAntialias($qsl_font_aa);
$draw->setTextAntialias($qsl_font_aa);

if($qsl_qso_center_gravity_multi){
	$image->setGravity(imagick::GRAVITY_CENTER);
}

if( $qsl_context == 2){
    include("qslprintmulti.php");
} else {
    include("qslprint.php");
}

$ff_page_title = $club_call;
$ff_header_content = sprintf("<h2>%s QSLs</h2>", $club_name);

$imgfile = sprintf("cards/%s.jpg", uniqid("$club_call-", true));
$image->writeImages($imgfile, true);
?>
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
<?php require_once("footer.php"); ?>
