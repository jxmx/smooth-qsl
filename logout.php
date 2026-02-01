<?php
/*
Copyright 2017-2025 Jason D. McCormick

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
include_once(__DIR__ . "/lib/include.php");

// Start the session so we can destroy it cleanly
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Wipe all session variables
$_SESSION = [];

// Delete the session cookie if cookies are used
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();

    setcookie(
        session_name(),
        '',
        [
            'expires'  => time() - 42000,
            'path'     => $params['path'],
            'domain'   => $params['domain'],
            'secure'   => $params['secure'],
            'httponly' => $params['httponly'],
            'samesite' => $params['samesite'] ?? 'Strict'
        ]
    );
}

// Destroy the session container
session_destroy();

// Redirect to login page
http_redirect('index.php');
