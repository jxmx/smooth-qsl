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

# club call
$club_call = "W8WKY";

# club name
$club_name = "Silvercreek Amateur Radio Association";

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

# ImageMagick type is one of php or cli. Use 'php' if your web server
# has the PHP module built int. Use 'cli' if PHP needs to shell out
# to the convert(1) command. If using 'cli', you may need to set
# 'im_clipath' to the location of convert(1).
$qsl_im_type = "cli";

# Where is the ImageMagick convert(1) program located? Most likely
# this is /usr/bin unless you're built a local installation.
$qsl_im_clipath = "/usr/bin";

# Where can ImageMagic convert(1) write output files temporarily
# before serving them? /tmp is usually a good choice but
# some webhosts may restrict that.
$qsl_im_tmpdir = "/tmp";

# When using $qsl_im_type=cli, instead of displaying the PDF display
# certain debugging information about the shellout call.
$qsl_im_debug = true;

# When using $qsl_im_type=cli, don't delete the generated
# PDF from $qsl_im_tmpdir. DO NOT USE 'true' IN PRODUCTION!
$qsl_im_nounlink = false;

# QSL Card Template for single QSO
$qsl_template = "SARA_W8WKY_40th_Blank.jpg";

# QSL Card Template for multi QSO (for same set = $qsl_tempalte)
$qsl_template_multi = "SARA_W8WKY_40th_Blank_Multi.jpg";

# QSL Card Callsign Font
$qsl_c_font = "Times-Bold";

# QSL Card Callsign Font Size
$qsl_c_font_size = 96;

# QSL Card Callsign Color in Hex
$qsl_c_font_color = "#000000";

# QSL Card QSO List Font Family/Name
$qsl_font = "Times-Roman";

# QSL Card QSO List Font Size in pixels
$qsl_font_size = 24;	

# QSL Card QSO List Font Color in Hex
$qsl_font_color = "#000000";

# QSL Card QSO List Font Anti-Aliasing (set boolean true or false)
$qsl_font_aa = true;
$qsl_c_font_aa = $qsl_font_aa;

# QSL Card Callsign Offsets - This is the offet, in pixels
# of the start of the upper-left corner of the text for the callsign.
# In images, the top left corner of the image is 0,0 and the greater
# the numbers the further down or right the position is. These
# settings control the beginning locations of the callsign print
# and the QSO log printing along with offset modifiers

# Should the callsign be center-offet or X/Y offset? Basically,
# if you are printing the callsign in a textflow-type format
# where you would want centered text then set this to true. If
# you are printing the callsign in a box then you want this
# to be false. One config for single and one for multi QOS formats.
$qsl_callsign_center_gravity = true;
$qsl_callsign_center_gravity_multi = true;

# The callsign prints once in one location so just provide a
# veritcal and horizontal offset. If $qsl_callsign_center_gravity = true
# then this is the offset from the center of the image, with positive
# values moving the text down and right while negative values move
# the text up and left.
$qsl_callsign_vert_offset = -35;
$qsl_callsign_horiz_offset = 0;

# Same as above but for the multi QSO certificate
$qsl_callsign_vert_offset_multi = -15;
$qsl_callsign_horiz_offset_multi = 0;


# How many rows of QSOs should be printed on the card?
$qsl_num_qso_rows = 5;

# Should the QSO row(s) be printed center-offset or
# X/Y offset? See $qsl_callsign_center_gravity.
$qsl_qso_center_gravity = true;
$qsl_qso_center_gravity_multi = false;

# Should the operator callsign be printed as part of the QSO record? Must
# be a true or false boolean value.
$qsl_qso_print_operator = true;
$qsl_qso_print_operator_multi = $qsl_qso_print_operator;

# Should the QSO record be verbose for the *SINGLE QSO* card? 
# If true, the QSO line will print as
#
#    YYYY-MM-DD hh:mmZ Freq:99.999 RST:59 Oper:CALL
# 
# If false only the values will print in the order Date, Time,
# Freq, RST, and Operator (if set). If this value is set to 
# true, then then values of $qsl_horiz_*_offset
# below are ignored and all text moves with $qsl_horiz_offset.
# One variable for single one for multi
$qsl_qso_verbose_rec = true;
$qsl_qso_verbose_rec_multi = true;

# For the single QSO, this is the offset on the page for
# where the QSO line will print. 0,0 is in the top left of the image
# if $qsl_qso_center_gravity = false and 0,0 is in the exact middle
# of the image of $qsl_qso_center_gravity = true. One set of variables
# for the single format and one for multi format.
$qsl_vert_offset = 15;
$qsl_horiz_offset = 0;
$qsl_vert_offset_multi = 400;
$qsl_horiz_offset_multi = 80;

# Set the horizontal offset for the Single QSO details. The date is 
# always first followed by time, band, rst, and operator. All offsets
# are relative to $qsl_horiz_offset. The values below can be
# standalone integers or you can do math based on other variables
# Note that $qsl_horiz_operator_offset is meaningless if 
# $qsl_qso_print_operator is set to false.
$qsl_horiz_timeon_offset = $qsl_horiz_offset + 100;
$qsl_horiz_band_offset = $qsl_horiz_timeon_offset + 100;
$qsl_horiz_rst_offset = $qsl_horiz_band_offset + 200;
$qsl_horiz_mode_offset = $qsl_horiz_rst_offset + 50;
$qsl_horiz_operator_offset = $qsl_horiz_mode_offset + 50;

# The multiline_multipier is where the next line to being for
# a multieline QSO output. This setting is no effect if
# $qsl_num_qso_rows is < 2. Usually $qsl_font_size + 3 is a good start
# and you usually want to base it on $qsl_font_size.
$qsl_multiline_multiplier = $qsl_font_size + 3;

# Set the horizontal offset for the Multi QSO details. The date is 
# always first followed by time, band, rst, and operator. All offsets
# are relative to $qsl_horiz_offset_multi. The values below can be
# standalone integers or you can do math based on other variables
# Note that $qsl_horiz_operator_offset_multi is meaningless if 
# $qsl_qso_print_operator is set to false.
$qsl_horiz_timeon_offset_multi = $qsl_horiz_offset + 100;
$qsl_horiz_band_offset_multi = $qsl_horiz_timeon_offset + 100;
$qsl_horiz_rst_offset_multi = $qsl_horiz_band_offset + 200;
$qsl_horiz_mode_offset_multi = $qsl_horiz_rst_offset + 50;
$qsl_horiz_operator_offset_multi = $qsl_horiz_mode_offset + 50;

# The /qsl/ Notes block
$qsl_page_note = '<div class="alert alert-info"><p><strong>Notes on QSLs:</strong></p><p>For paper QSL, please QSL via our manager K
D8DEB. SASE is appreciated but not required.</p><p>At this time the club does not upload to LoTW or eQSL.</p> </div>';


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
