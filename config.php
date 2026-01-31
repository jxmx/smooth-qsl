<?php
/*
Copyright 2017-2026 Jason D. McCormick

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
$club_call = "Q1ABC";

# club name
$club_name = "Questionable ARC";

# MySQL Server (most likely "localhost")
$db_server = "localhost";

# MySQL Database Name
$db_db = "fireflyqsl";

# MySQL Access User (must have SELECT and INSERT privileges on the DB)
$db_user = "fireflyqsl";

# MySQL Access Password
$db_pass = "fireflyqsl";

# ADIF Load Key - This has to be at least 8 characters and three classes
$qsl_load_key = "Q129!0jalkj32i@";

# QSL Card Template for single QSO
$qsl_template = "fake.jpg";

# QSL Card Template for multi QSO (for same set = $qsl_tempalte)
$qsl_template_multi = $qsl_template;

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

# QSL Card Callsign Offsets - This is the offset, in pixels
# of the start of the upper-left corner of the text for the callsign.
# In images, the top left corner of the image is 0,0 and the greater
# the numbers the further down or right the position is. These
# settings control the beginning locations of the callsign print
# and the QSO log printing along with offset modifiers

# Should the callsign be center-offset or X/Y offset? Basically,
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
$qsl_callsign_vert_offset = 175;
$qsl_callsign_horiz_offset = 0;

# Same as above but for the multi QSO certificate
$qsl_callsign_vert_offset_multi = 175;
$qsl_callsign_horiz_offset_multi = 0;


# How many rows of QSOs should be printed on the card?
$qsl_num_qso_rows = 4;

# Should the QSO row(s) be printed center-offset or
# X/Y offset? See $qsl_callsign_center_gravity.
$qsl_qso_center_gravity = true;
$qsl_qso_center_gravity_multi = false;

# Should the operator callsign be printed as part of the QSO record? Must
# be a true or false boolean value.
$qsl_qso_print_operator = true;
$qsl_qso_print_operator_multi = $qsl_qso_print_operator;
$qsl_qso_print_opercounty = true;
$qsl_qso_print_opercounty_multi = $qsl_qso_print_opercounty;

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
$qsl_vert_offset = 690;
$qsl_horiz_offset = 200;
$qsl_vert_offset_multi = 650;
$qsl_horiz_offset_multi = 200;

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
$qsl_horiz_county_offset = $qsl_horiz_operator_offset + 50;

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
$qsl_horiz_timeon_offset_multi = $qsl_horiz_offset_multi + 100;
$qsl_horiz_band_offset_multi = $qsl_horiz_timeon_offset_multi + 100;
$qsl_horiz_rst_offset_multi = $qsl_horiz_band_offset_multi + 200;
$qsl_horiz_mode_offset_multi = $qsl_horiz_rst_offset_multi + 50;
$qsl_horiz_operator_offset_multi = $qsl_horiz_mode_offset_multi + 50;
$qsl_horiz_county_offset_multi = $qsl_horiz_operator_offset_multi + 50;

# The /qsl/ Notes block
$qsl_page_note = '<p><strong>Notes on QSLs:</strong></p><p>For paper QSL, please QSL via our manager
Q1XYZ. SASE is appreciated but not required.</p><p>Electronic logs available from LoTW.</p>';



