<?php
$lcount = 0;
foreach($rows as $row){

	if($qsl_qso_verbose_rec_multi){

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
		if(is_int(strlen($row['rstrcvd']))){
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

		# QSO County
		if($qsl_qso_print_opercounty_multi){
			$draw->annotation(
				$qsl_horiz_offset_multi + $qsl_horiz_county_offset_multi,
				$qsl_vert_offset_multi + ($lcount * $qsl_multiline_multiplier),
				$row['county']
				);
		}
}

	$image->drawImage($draw);
	$lcount++;
}