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
$image = new Imagick($qsl_template_multi);
$image->setImageFormat('pdf');

# Draw the Callsign
$draw = new ImagickDraw();
$color = new ImagickPixel($qsl_c_font_color);
$draw->setFont($qsl_c_font);
$draw->setFontSize($qsl_c_font_size);
$draw->setFillColor($color);
$draw->setStrokeAntialias($qsl_c_font_aa);
$draw->setTextAntialias($qsl_c_font_aa);
if($qsl_callsign_center_gravity_multi){
	$image->setGravity(imagick::GRAVITY_CENTER);
}
$draw->annotation($qsl_callsign_horiz_offset_multi, $qsl_callsign_vert_offset_multi, $call);
$image->drawImage($draw);

if($qsl_callsign_center_gravity_multi){
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

$lcount = 0;
while($row = $res->fetch_assoc()){	

	if($qsl_qso_verbose_rec_multi){
		
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
		$draw->annotation($qsl_horiz_offset_multi, 
			$qsl_vert_offset_multi + ($lcount * $qsl_multiline_multiplier), $qstring);

	} else {

		# QSO Date
		$draw->annotation(
			$qsl_horiz_offset_multi, 
			$qsl_vert_offset_multi + ($lcount * $qsl_multiline_multiplier), 
			$row['qsodate']
			);
		
		# QSO Time
		$draw->annotation(
			$qsl_horiz_offset_multi + $qsl_horiz_timeon_offset, 
			$qsl_vert_offset_multi + ($lcount * $qsl_multiline_multiplier), 
			$row['timeon'] . "Z"
			);
			
		# QSO Band + Freq
        $freqband = "";
        if(strlen($row['freq'] > 0)){
            $freqband = sprintf("%.03f", $row['freq']);
        } else {
            $freqband = $row['band'];
        }
		$draw->annotation(
			$qsl_horiz_offset_multi + $qsl_horiz_band_offset,
			$qsl_vert_offset_multi + ($lcount * $qsl_multiline_multiplier), 
			$freqband
			);
		
		# QSO RST
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

		$draw->annotation(
			$qsl_horiz_offset_multi + $qsl_horiz_rst_offset_multi, 
			$qsl_vert_offset_multi + ($lcount * $qsl_multiline_multiplier),
			$rst
			);

		# QSO Mode
		$draw->annotation(
			$qsl_horiz_offset_multi + $qsl_horiz_mode_offset_multi, 
			$qsl_vert_offset_multi + ($lcount * $qsl_multiline_multiplier),
			$row['mode']
			);
		
		# QSO Operator
		if($qsl_qso_print_operator_multi){
			$draw->annotation(
				$qsl_horiz_offset_multi + $qsl_horiz_operator_offset_multi, 
				$qsl_vert_offset_multi + ($lcount * $qsl_multiline_multiplier),
				$row['operator']
				);
		}
	
	}
		
	$image->drawImage($draw);
	$lcount++;
}

include_once("qslprintpost.php");
?>
