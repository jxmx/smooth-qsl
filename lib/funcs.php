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



/** checks if a callsign is valid */
function is_callsign($callsign){
    $callsign_pattern = '/(?:[A-Z]{1,2}|[0-9][A-Z])\d{1,2}[A-Z]{1,4}(?:\/[A-Z0-9]+)?$/i';
    if( preg_match($callsign_pattern, $callsign)){
        return true;
    }
    return false;
}

/** returns an HTTP error and stops the processing */
function http_error_response($code, $message){
	http_response_code($code);
	header("Content-Type: text/plain; charset=utf-8");
	printf("%d %s", $code, $message);
	exit;
}

/**
 * Perform an HTTP redirect and stop execution.
 *
 * @param string $url  The target URL (absolute or relative)
 * @param int    $code HTTP status code (301, 302, 303, 307, 308)
 */
function http_redirect(string $url, int $code = 302): void
{
    // Basic sanity check
    if (headers_sent()) {
        throw new RuntimeException("Cannot redirect; headers already sent.");
    }

    http_response_code($code);
    header("Location: {$url}");
    exit;
}

/** for privileged pages */
function require_login(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    if (empty($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {

        // Current request path + query
        $current = $_SERVER['REQUEST_URI'];

        // Only allow internal paths that start with "/"
        // This prevents: http://evil.com, //evil.com, javascript:, etc.
        if (!preg_match('#^/[A-Za-z0-9/_\-.?=&]*$#', $current)) {
            http_error_response(400, "invalid page redirection");
        }

        $next = urlencode($current);

        http_redirect("login.php?nextpage={$next}");
        exit;
    }
}




function adif_to_array($adiffile, $csign, $location){

    $adif = new ADIF_Parser;
    $adif->load_from_file($adiffile);
    $adif->initialize();

    $log = [];

    while($rec = $adif->get_record()){
        if(count($rec) == 0){
            return false;
        }

        $a_date = preg_replace('/^([0-9]{4})([0-9]{2})([0-9]{2})$/', '$1-$2-$3',
            clean_int($rec["qso_date"]));

        $a_time = preg_replace('/^([0-9]{2})([0-9]{2})([0-9]{2})$/', '$1:$2',
            strcleaner($rec["time_on"]));

        $a_call = strtoupper(strcleaner($rec["call"]));

        $a_freq = clean_float($rec["freq"]);

        $a_band = isset($rec["band"]) ? strcleaner($rec["band"]) : "";

        $a_mode = strcleaner($rec["mode"]);

        $a_rst  = strcleaner($rec["rst_rcvd"]);

        $a_oper = (!isset($rec["operator"]) || strlen($rec["operator"]) == 0)
            ? $csign
            : strcleaner($rec["operator"]);

        if( isset($rec["comment"]) ){
            $a_comment = strcleaner($rec["comment"]);
        } else {
            $a_comment = "";
        }

        // Build the normalized record
        $log[] = [
            "call"      => $a_call,
            "band"      => $a_band,
            "freq"      => $a_freq,
            "rst"       => $a_rst,
            "date"      => $a_date,
            "time"      => $a_time,
            "operator"  => $a_oper,
            "station"   => $csign,
            "mode"      => $a_mode,
            "location"  => $location,
            "comment"   => $a_comment
            ];
    }

    return $log;
}
