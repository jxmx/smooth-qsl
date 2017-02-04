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

# club callsign
$club_call = "XXXXX";

# club name
$club_name = "An Amateur Radio Association";

# MySQL Server (most likely "localhost")
$db_server = "localhost";

# MySQL Database Name
$db_db = "qsl";

# MySQL Access User (must have SELECT and INSERT privileges on the DB)
$db_user = "qsl";

# MySQL Access Password
$db_pass = "qsl";

# ADIF Load Key
$qsl_load_key = "xxyyzz";

# QSL Card Template - Complete path to the template file on disk
$qsl_template = "fake.jpg";

# QSL Card Callsign Font
$qsl_c_font = "FreeMono";

# QSL Card Callsign Font Size
$qsl_c_font_size = 172;

# QSL Card Callsign Color in Hex
$qsl_c_font_color = "#FF0000";

# QSL Card QSO List Font Family/Name
$qsl_font = "FreeMono";

# QSL Card QSO List Font Size in pixels
$qsl_font_size = 24;

# QSL Card QSO List Font Color in Hex
$qsl_font_color = "#0000FF";

# QSL Card QSO List Font Anti-Aliasing (set boolean true or false)
$qsl_font_aa = true;
$qsl_c_font_aa = $qsl_font_aa;

# QSL Card Callsign Offsets - This is the offet, in pixels
# of the start of the upper-left corner of the text for the callsign.
# In images, the top left corner of the image is 0,0 and the greater
# the numbers the further down or right the position is. These
# settings control the beginning locations of the callsign print
# and the QSO log printing along with offset modifiers

# The callsign prints once in one location so just provide a
# veritcal and horizontal offset
$qsl_callsign_vert_offset = 400;
$qsl_callsign_horiz_offset = 100;

# How many rows of QSOs should be printed on the card
$qsl_num_qso_rows = 5;

# Should the operator callsign be printed as part of the QSO record? Must
# be a true or false boolean value.
$qsl_qso_print_operator = true;

# The QSO list prints as offsets with multipliers for each item
# and then a multiplier for the next line for multi-line printing.
# Set the base offset for vertical and horizontal positioning
$qsl_vert_offset = 650;
$qsl_horiz_offset = 50;

# The multiline_multipier is where the next line to being for
# a multieline QSO output. This setting is no effect if
# $qsl_num_qso_rows is < 2. Usually $qsl_font_size + 3 is a good start
# and you usually want to base it on $qsl_font_size.
$qsl_multiline_multiplier = $qsl_font_size + 3;

# Set the horizontal offset for the QSO details. The date is 
# always first followed by time, band, rst, and operator. All offsets
# are relative to $qsl_horiz_offset. The values below can be
# standalone integers or you can do math based on other variables
# Note that $qsl_horiz_operator_offset is meaningless if 
# $qsl_qso_print_operator is set to false.
$qsl_horiz_timeon_offset = $qsl_horiz_offset + 100;
$qsl_horiz_band_offset = $qsl_horiz_timeon_offset + 100;
$qsl_horiz_rst_offset = $qsl_horiz_band_offset + 200;
$qsl_horiz_operator_offset = $qsl_horiz_rst_offset + 50;


###
### DO NOT EDIT BELOW HERE
###

function numcleaner($in){
	return filter_var($in, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_SCIENTIFIC);
}

function strcleaner($in){
	return filter_var($in, FILTER_SANITIZE_STRING,
		FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH |
		FILTER_FLAG_STRIP_BACKTICK | FILTER_FLAG_ENCODE_AMP );
}

?>
