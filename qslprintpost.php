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

$conn->close();

header('Content-Type: application/pdf');
header(sprintf("Content-Disposition: inline; filename=\"%s\"", 
	sprintf("%s_QSL_Card.pdf", $club_call)));

if(strcmp($qsl_im_type, "php") === 0){
	echo $image;
} else {
	print file_get_contents($outf);
	unlink($outf);
}

?>
