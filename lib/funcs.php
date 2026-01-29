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

/** Sanitized strings from input */
function strcleaner(?string $in): string {
    if ($in === null) {
        return '';
    }
    // Trim whitespace and normalize line endings
    $in = trim($in);
    // Remove control characters except tab/newline
    $in = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $in);
    // Optionally enforce UTF‑8 validity
    $in = mb_convert_encoding($in, 'UTF-8', 'UTF-8');
    return $in;
}

/** sanitizes input that should be integers */
function clean_int($in): ?int {
    $in = trim((string)$in);
    return filter_var($in, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
}

/** sanitizes input that should be floats */
function clean_float($in): ?float {
    $in = trim((string)$in);
    return filter_var($in, FILTER_VALIDATE_FLOAT, FILTER_NULL_ON_FAILURE);
}

?>
