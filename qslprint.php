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
<?php 
include_once "qslconf.php"; 

# Sanitize the call variable against injection
if( preg_match('/^[A-Za-z0-9\/]+$/', strcleaner($_POST["call"])) == 0){
	echo "<html><head><title>ERROR</title></head><body><h1>Invalid Parameters Error</h1></body></html>";
	exit;
}
$call = strtoupper(strcleaner($_POST["call"]));

# Build the "IN" array for the SELECT statement, checking the items for safety
$qs = "";
if(isset($_POST['qq'])){
	$qsos = $_POST['qq'];
	for($i = 0; $i < count($qsos); $i++){
		if(strlen($qs) == 0){
			if(numcleaner($qsos[$i])){
				$qs .= numcleaner($qsos[$i]);
			} else {
				echo "<html><head><title>ERROR</title></head><body><h1>Invalid Parameters Error</h1></body></html>";
				exit;				
			}
		} else {
			if(numcleaner($qsos[$i])){
				$qs .= "," . numcleaner($qsos[$i]);
			} else {
				echo "<html><head><title>ERROR</title></head><body><h1>Invalid Parameters Error</h1></body></html>";
				exit;				
			}
		}
	}
} else {
	echo "<html><head><title>ERROR</title></head><body><h1>Invalid Parameters Error</h1></body></html>";
	exit;
}

# Do SQL Dance
$sql = sprintf("SELECT * FROM qsos WHERE qsoid IN (%s)", $qs);
$conn = new mysqli($db_server, $db_user, $db_pass, $db_db);
if( $conn->connect_error){
        die("Connection failed: " . $conn->connect_error);
}
$res = $conn->query($sql);

# Intialize the ImageMagick item
$image = new Imagick($qsl_template);
$image->setImageFormat('pdf');

# Draw the Callsign
$draw = new ImagickDraw();
$color = new ImagickPixel($qsl_c_font_color);
$draw->setFont($qsl_c_font);
$draw->setFontSize($qsl_c_font_size);
$draw->setFillColor($color);
$draw->setStrokeAntialias($qsl_c_font_aa);
$draw->setTextAntialias($qsl_c_font_aa);
$draw->annotation($qsl_callsign_horiz_offset, $qsl_callsign_vert_offset, $call);
$image->drawImage($draw);

# Draw the QSO(s)
$draw = new ImagickDraw();
$color = new ImagickPixel($qsl_font_color);
$draw->setFont($qsl_font);
$draw->setFontSize($qsl_font_size);
$draw->setFillColor($color);
$draw->setStrokeAntialias($qsl_font_aa);
$draw->setTextAntialias($qsl_font_aa);

$lcount = 0;
while($row = $res->fetch_assoc()){	

	# QSO Date
	$draw->annotation(
		$qsl_horiz_offset, 
		$qsl_vert_offset + ($lcount * $qsl_multiline_multiplier), 
		$row['qsodate']
		);
	
	# QSO Time
	$draw->annotation(
		$qsl_horiz_offset + $qsl_horiz_timeon_offset, 
		$qsl_vert_offset + ($lcount * $qsl_multiline_multiplier), 
		$row['timeon'] . "Z"
		);
		
	# QSO Band + Freq
	$freqband = "";
	if(strlen($row['freq'] > 0)){
			$freqband = $row['freq'];
		} else {
			$freqband = $row['band'];
		}
	$draw->annotation(
		$qsl_horiz_offset + $qsl_horiz_band_offset,
		$qsl_vert_offset + ($lcount * $qsl_multiline_multiplier), 
		$freqband
		);
	
	# QSO RST
	$draw->annotation(
		$qsl_horiz_offset + $qsl_horiz_rst_offset, 
		$qsl_vert_offset + ($lcount * $qsl_multiline_multiplier),
		$row['rstrcvd']
		);
		
	# QSO Operator
	if($qsl_qso_print_operator){
		$draw->annotation(
			$qsl_horiz_offset + $qsl_horiz_operator_offset, 
			$qsl_vert_offset + ($lcount * $qsl_multiline_multiplier),
			$row['operator']
			);
	}
		
	$image->drawImage($draw);
	$lcount++;
}

$conn->close();

# Emit!
header('Content-Type: application/pdf');
header(sprintf("Content-Disposition: inline; filename=\"%s\"", 
	sprintf("%s_QSL_Card.pdf", $club_call)));
echo $image;

?>