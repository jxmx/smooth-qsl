<?php
$row = $rows[0];
if($qsl_qso_verbose_rec){

	$freqband = "";
	if(strlen($row['freq'] > 0)){
		$freqband = sprintf("%.03f", $row['freq']);
	} else {
		$freqband = $row['band'];
	}

	$rst = "";
	if(is_int(strlen($row['rstrcvd']))){
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
	if($qsl_qso_print_opercounty){
		$qstring .= sprintf("  QTH: %s", $row['location']);
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
	if(is_int(strlen($row['rstrcvd']))){
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

	if($qsl_qso_print_opercounty){
		$draw->annotation($qsl_horiz_offset + $qsl_horiz_county_offset, $qsl_vert_offset, $row['county']);
	}
}

$image->drawImage($draw);
