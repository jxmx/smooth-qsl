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
include_once "qslprintpre.php";

# Intialize the ImageMagick item
if(strcmp($qsl_im_type,"php") === 0){
	$image = new Imagick($qsl_template);
	$image->setImageFormat('pdf');
} elseif(strcmp($qsl_im_type,"cli") === 0){
	$icli = sprintf("%s/convert %s", $qsl_im_clipath, $qsl_template);
} else {
	echo "invalid ImageMagick type";
	exit;
}

# Draw the Callsign
if(strcmp($qsl_im_type, "php") === 0){
	$draw = new ImagickDraw();
	$color = new ImagickPixel($qsl_c_font_color);
	$draw->setFont($qsl_c_font);
	$draw->setFontSize($qsl_c_font_size);
	$draw->setFillColor($color);
	$draw->setStrokeAntialias($qsl_c_font_aa);
	$draw->setTextAntialias($qsl_c_font_aa);
	if($qsl_callsign_center_gravity){
		$image->setGravity(imagick::GRAVITY_CENTER);
	}
	$draw->annotation($qsl_callsign_horiz_offset, $qsl_callsign_vert_offset, $call);
	$image->drawImage($draw);

	if($qsl_callsign_center_gravity){
		$image->setGravity(imagick::GRAVITY_NORTHWEST);
	}
} else {
	$icli = sprintf("%s -pointsize %d -fill '%s' -stroke '%s' -font '%s'",
		$icli, $qsl_c_font_size, $qsl_c_font_color, $qsl_c_font_color, $qsl_c_font);
	 if($qsl_callsign_center_gravity){
		$icli = sprintf("%s -gravity %s", $icli, "Center");
    }
	$icli = sprintf("%s -draw 'text %d,%d \"%s\"'",
		$icli, $qsl_callsign_horiz_offset, $qsl_callsign_vert_offset, $call);

}

if(strcmp($qsl_im_type, "php") === 0){
	# Draw the QSO(s)
	$draw = new ImagickDraw();
	$color = new ImagickPixel($qsl_font_color);
	$draw->setFont($qsl_font);
	$draw->setFontSize($qsl_font_size);
	$draw->setFillColor($color);
	$draw->setStrokeAntialias($qsl_font_aa);
	$draw->setTextAntialias($qsl_font_aa);

	if($qsl_qso_center_gravity){
		$image->setGravity(imagick::GRAVITY_CENTER);
	}	

	$row = $res->fetch_assoc();
	if($qsl_qso_verbose_rec){
		
		$freqband = "";
		if(strlen($row['freq'] > 0)){
			$freqband = sprintf("%.03f", $row['freq']);
		} else {
			$freqband = $row['band'];
		}
	
		$rst = "";
		if(strlen($row['rstrcvd'] > 0)){
			$rst = $row['rstrcvd'];
		} else {
			if(strcmp($row['mode'], "CW") or strcmp($row['mode'], "cw")){
				$rst = "599";
			} else {
				$rst = "59";
			}
		}
		
	
		$qstring = sprintf("%s %sZ  Freq: %sMhz  RST: %s  Mode: %s",
			$row['qsodate'], $row['timeon'], $freqband, $rst, $row['mode']);
		if($qsl_qso_print_operator){
			$qstring .= sprintf("  Oper: %s", $row['operator']);
		}
		$draw->annotation($qsl_horiz_offset, $qsl_vert_offset, $qstring);
	
	} else {
	
		$draw->annotation($qsl_horiz_offset, $qsl_vert_offset, $row['qsodate']);
		$draw->annotation($qsl_horiz_offset + $qsl_horiz_timeon_offset, $qsl_vert_offset, $row['timeon'] . "Z");
			
		$freqband = "";
		if(strlen($row['freq'] > 0)){
			$freqband = sprintf("%.03f", $row['freq']);
		} else {
			$freqband = $row['band'];
		}
		$draw->annotation($qsl_horiz_offset + $qsl_horiz_band_offset, $qsl_vert_offset, $freqband);
		
		$rst = "";
		if(strlen($row['rstrcvd'] > 0)){
			$rst = $row['rstrcvd'];
		} else {
			if(strcmp($row['mode'], "CW") or strcmp($row['mode'], "cw")){
				$rst = "599";
			} else {
				$rst = "59";
			}
		}
	
		$draw->annotation($qsl_horiz_offset + $qsl_horiz_rst_offset, $qsl_vert_offset, $rst);
		$draw->annotation($qsl_horiz_offset + $qsl_horiz_mode_offset, $qsl_vert_offset, $row['mode']);
		
		if($qsl_qso_print_operator){
			$draw->annotation($qsl_horiz_offset + $qsl_horiz_operator_offset, $qsl_vert_offset, $row['operator']);
		}
	}
			
	$image->drawImage($draw);
} else {

$outft = tempnam($qsl_im_tmpdir,sprintf("%s_qsl", $club_call));
$outf = sprintf("%s.pdf", $outft);
rename($outft,$outf);
$icli = sprintf("%s %s", $icli, $outf);
}

include_once("qslprintpost.php");
?>
